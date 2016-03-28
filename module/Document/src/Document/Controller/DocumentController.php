<?php
namespace Document\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Document\Form\DocumentForm; 
use Document\Form\DocumentFormValidator; 
use Document\Model\Document; 

class DocumentController extends AbstractActionController
{
    protected $documentTable;

    public function getDocumentTable()
    {
        if (!$this->documentTable) {
            $sm = $this->getServiceLocator();
            $this->documentTable = $sm->get('Document\Model\DocumentTable');
        }
        return $this->documentTable;
    }    

    public function indexAction()
    {
    }

    public function viewAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) 
        {
            return $this->redirect()->toRoute('document', array('action' => 'add'));
        }

        $user_session = new Container('user');

        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->condominium_id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/

        try 
        {
            $document = $this->getDocumentTable()->getDocument($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('document', array(
                'action' => 'index'
            ));
        }

        header("Cache-Control: maxage=1"); //In seconds
        header("Pragma: public");
        header("Content-length: $document->size");
        header("Content-type: $document->mime");
        header("Content-Disposition: attachment;filename=\"$document->filename\"");
        header("Content-Description: PHP Generated Data");
        header("Content-transfer-encoding: binary");        
        ob_end_clean();
        flush();
        echo $document->data;
        
    }

    public function listAction()
    {
        $this->checkSession();

        $user_session = new Container('user');

        $category_id = (int) $this->params()->fromRoute('id', 0);

        if (!$category_id) 
            return $this->redirect()->toRoute('category', array('action' => 'index'));

        try 
        {
            $category = $this->getCategory($category_id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('category', array('action' => 'index'));
        }

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        return new ViewModel(array(
            'category' => $category,
            'documents' => $this->getDocumentTable()->getDocumentByCategory($user_session->condominium_id, $category_id)
        ));
    }

    public function addAction()
    {
        $this->checkSession();
        $user_session = new Container('user');

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $request = $this->getRequest();

        if (!$request->isPost()) 
        {
            $category_id = (int) $this->params()->fromRoute('id', 0);
        }
        else
        {
            $category_id = (int) $_POST['category_id'];
        }

        if (!$category_id) 
            return $this->redirect()->toRoute('category', array('action' => 'index'));

        $form = new DocumentForm();
        $form->get('submit')->setValue('inserisci');
        $form->get('category_id')->setValue($category_id);
        $form->get('condominium_id')->setValue($user_session->condominium_id);

        if ($request->isPost()) 
        {
            $document = new Document();
            $form->setInputFilter($document->getInputFilter());
            $data    = array_merge_recursive(
                          $this->getRequest()->getPost()->toArray(),           
                          $this->getRequest()->getFiles()->toArray()
                   );
            $form->setData($data);

            if ($form->isValid()) 
            {
                if(isset($_POST['submit']) && $_FILES['data']['size'] > 0)
                {
                    $filename = $_FILES['data']['name'];
                    $tmpname  = $_FILES['data']['tmp_name'];
                    $filesize = $_FILES['data']['size'];
                    $filetype = $_FILES['data']['type'];
                    
                    $fp      = fopen($tmpname, 'r');
                    $content = fread($fp, $filesize);
                    /*
                    if(!get_magic_quotes_gpc())
                        $content = addslashes($content);
                    */
                    fclose($fp);

                    if(!get_magic_quotes_gpc())
                        $filename = addslashes($filename);

                    /*
                    echo $filename . '<br />';
                    echo $filesize . '<br />';
                    echo $filetype . '<br />';
                    echo $content . '<br />';
                    */
                    $document->exchangeArray($form->getData());
                    $document->filename = $filename;
                    $document->size = $filesize;
                    $document->mime = $filetype;
                    $document->data = $content;
                    $document->creationdate = date('Y-m-d H:i:s');
                    $document->modifydate = date('Y-m-d H:i:s');
                    $this->getDocumentTable()->saveDocument($document);
                }

                // Redirect to list of documents
                return $this->redirect()->toRoute('document', array('action' => 'list', 'id' => $document->category_id));
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) 
        {
            return $this->redirect()->toRoute('document', array(
                'action' => 'add'
            ));
        }

        try 
        {
            $document = $this->getDocumentTable()->getDocument($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('document', array(
                'action' => 'index'
            ));
        }
        
        $form = new DocumentForm();
        $form->bind($document);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $form->setInputFilter($document->getEditInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $document = $form->getData();
                $document->modifydate = date('Y-m-d H:i:s');
                $this->getDocumentTable()->saveDocument($document);

                // Redirect to list of documents
                return $this->redirect()->toRoute('document', array('action' => 'list', 'id' => $document->category_id));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $this->checkSession();
        
        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('category');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            $category_id = (int) $request->getPost('category_id');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getDocumentTable()->deleteDocument($id);
            }

            // Redirect to list of documents
            return $this->redirect()->toRoute('document', array('action' => 'list', 'id' => $category_id));
        }

        return array(
            'id'    => $id,
            'document' => $this->getDocumentTable()->getDocument($id)
        );
    }

    private function getAvailableCondominium()
    {
        $sm = $this->getServiceLocator();
        $condominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        
        $user_session = new Container('user');

        return  $condominiumTable->getCondominiumByUser($user_session->type, $user_session->id, $user_session->condominiums);    
    }

    private function getCategory($id)
    {
        $sm = $this->getServiceLocator();
        $categoryTable = $sm->get('Category\Model\CategoryTable');
        return  $categoryTable->getCategory($id);    
        
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(!isset($user_session->id))
            return $this->redirect()->toRoute('application', array('action' => 'index'));
        
    }

    private function checkPrivileges($id, $condominium_id)
    {
        return $this->getDocumentTable()->checkPrivileges($id, $condominium_id);
    }


}
