<?php
namespace Condominium\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Condominium\Form\CondominiumForm; 
use Condominium\Form\CondominiumFormValidator; 
use Condominium\Model\Condominium;
use Common\Draw;

class CondominiumController extends AbstractActionController
{

    protected $condominiumTable;

    public function getCondominiumTable()
    {
        if (!$this->condominiumTable) 
        {
            $sm = $this->getServiceLocator();
            $this->condominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        }
        return $this->condominiumTable;
    }

    public function indexAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
        
        $user_session = new Container('user');

        return new ViewModel(array(
            'condominium' => $this->getCondominiumTable()->getCondominium($user_session->condominium_id),
            'registered' => $this->getRegisteredCondomino()->count(),
            'adviser' => $this->getAdviserCondomino()
        ));   
    }

    public function addAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form = new CondominiumForm();
        $form->get('submit')->setValue('inserisci');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $condominium = new Condominium();
            $form->setInputFilter($condominium->getInputFilter());
            $data = array_merge_recursive(
                          $this->getRequest()->getPost()->toArray(),           
                          $this->getRequest()->getFiles()->toArray()
                   );
            $form->setData($data);

            if ($form->isValid()) 
            {
                $user_session = new Container('user');
                $condominium->exchangeArray($form->getData());
                $condominium->administrator_id = $user_session->id;
                $condominium->creationdate = date('Y-m-d H:i:s');
                $condominium->modifydate = date('Y-m-d H:i:s');
                $this->getCondominiumTable()->saveCondominium($condominium);
                $lastid = $this->getCondominiumTable()->getLastID();
                
                if($_FILES['picture']['size'] > 0)
                {
                    $this->saveCondomoniumPicture($_FILES['picture']['tmp_name'], $lastid);
                }

                $user_session->condominium_id = $lastid;
 
                // Redirect to list of condominiums
                return $this->redirect()->toRoute('condominium');
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
            return $this->redirect()->toRoute('condominium', array('action' => 'add'));
        }

        try 
        {
            $condominium = $this->getCondominiumTable()->getCondominium($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('condominium', array(
                'action' => 'index'
            ));
        }

        $form  = new CondominiumForm();
        $form->bind($condominium);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $form->setInputFilter($condominium->getInputFilter());
            $data = array_merge_recursive(
                          $this->getRequest()->getPost()->toArray(),           
                          $this->getRequest()->getFiles()->toArray()
                   );
            $form->setData($data);

            if ($form->isValid()) 
            {
                $user_session = new Container('user');
                $condominium = $form->getData();
                $condominium->administrator_id = $user_session->id;
                $condominium->modifydate = date('Y-m-d H:i:s');
                $this->getCondominiumTable()->saveCondominium($condominium);

                if($_FILES['picture']['size'] > 0)
                {
                    $this->saveCondomoniumPicture($_FILES['picture']['tmp_name'], $condominium->id);
                }

                // Redirect to list of condominiums
                return $this->redirect()->toRoute('condominium');
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
            return $this->redirect()->toRoute('condominium');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getCondominiumTable()->deleteCondominium($id);
                
                $firstid = $this->getCondominiumTable()->getFirstID();

                $user_session = new Container('user');
                
                if($firstid > 0)
                {
                    $user_session->condominium_id = $firstid;        
                }
                
            }

            // Redirect to list of condominiums
            //return $this->redirect()->toRoute('condominium');
            return $this->redirect()->toRoute('administrator', array('action' => 'home'));
        }

        return array(
            'id'    => $id,
            'condominium' => $this->getCondominiumTable()->getCondominium($id)
        );
    }

    public function resetpictureAction()
    {
        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) 
        {
            return $this->redirect()->toRoute('condominium');
        }

        unlink('./img/condominium/' . base64_encode('picture_' . $id) . '.jpeg');
    
        return $this->redirect()->toRoute('condominium', array('action' => 'edit', 'id' => $id));
    
    }

    private function getAvailableCondominium()
    {
        $sm = $this->getServiceLocator();
        $condominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        
        $user_session = new Container('user');

        return  $condominiumTable->getCondominiumByUser($user_session->type, $user_session->id, $user_session->condominiums);    
    }

    private function getRegisteredCondomino()
    {
        $sm = $this->getServiceLocator();
        $condominoTable = $sm->get('Condomino\Model\CondominoTable');
        
        $user_session = new Container('user');

        return  $condominoTable->getRegisteredCondomino($user_session->condominium_id);    
    }

    private function getAdviserCondomino()
    {
        $sm = $this->getServiceLocator();
        $condominoTable = $sm->get('Condomino\Model\CondominoTable');
        
        $user_session = new Container('user');

        return  $condominoTable->getAdviserCondomino($user_session->condominium_id);    
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(!isset($user_session->id))
            return $this->redirect()->toRoute('application', array('action' => 'index'));
        
    }

    private function saveCondomoniumPicture ($uploaded_file, $condominium_id)
    {
        list($width, $height, $type, $attr) = getimagesize($uploaded_file); 
        
        if($width > 250)
        {
            $image = new Draw();
            $image->load($uploaded_file); 
            $image->resizeToWidth(250); 
            $image->save($uploaded_file); 
        }

        if($height > 250)
        {
            $image = new Draw();
            $image->load($uploaded_file); 
            $image->resizeToHeight(250); 
            $image->save($uploaded_file); 
        }

        
        $filename  = base64_encode('picture_' . $condominium_id);

        switch ($_FILES["picture"]["type"])
        {
                case "image/jpeg":
                $ext = 'jpeg';
                break;
                case "image/jpg":
                $ext = 'jpg';
                break;
        }
        // force extension
        $ext = 'jpeg';
        move_uploaded_file($uploaded_file, "./img/condominium/" . $filename . '.' . $ext);
    }

}