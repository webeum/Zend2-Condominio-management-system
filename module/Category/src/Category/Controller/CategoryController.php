<?php
namespace Category\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Category\Form\CategoryForm; 
use Category\Form\CategoryFormValidator; 
use Category\Model\Category; 

class CategoryController extends AbstractActionController
{
    protected $categoryTable;

    public function getCategoryTable()
    {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('Category\Model\CategoryTable');
        }
        return $this->categoryTable;
    }    

    public function indexAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        return new ViewModel(array(
            'categories' => $this->getCategoryTable()->getCategoryOrderByRanking(),
        ));
    }

    public function addAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form = new CategoryForm();
        $form->get('submit')->setValue('inserisci');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $category = new Category();
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $category->exchangeArray($form->getData());
                $category->creationdate = date('Y-m-d H:i:s');
                $category->modifydate = date('Y-m-d H:i:s');
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of categories
                return $this->redirect()->toRoute('category');
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
            return $this->redirect()->toRoute('category', array(
                'action' => 'add'
            ));
        }

        try 
        {
            $category = $this->getCategoryTable()->getCategory($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('category', array(
                'action' => 'index'
            ));
        }
        
        
        $form = new CategoryForm();
        $form->bind($category);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $form->setInputFilter($category->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $category = $form->getData();
                $category->modifydate = date('Y-m-d H:i:s');
                $this->getCategoryTable()->saveCategory($category);

                // Redirect to list of categories
                return $this->redirect()->toRoute('category');
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

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->deleteDocuments($id);
                $this->getCategoryTable()->deleteCategory($id);
            }

            // Redirect to list of categories
            return $this->redirect()->toRoute('category');
        }

        return array(
            'id'    => $id,
            'category' => $this->getCategoryTable()->getCategory($id)
        );
    }

    private function deleteDocuments($category_id)
    {
        $sm = $this->getServiceLocator();
        $documentTable = $sm->get('Document\Model\DocumentTable');
        return  $documentTable->deleteDocumentByCategory($category_id);    
    }

    private function getAvailableCondominium()
    {
        $sm = $this->getServiceLocator();
        $condominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        
        $user_session = new Container('user');

        return  $condominiumTable->getCondominiumByUser($user_session->type, $user_session->id, $user_session->condominiums);    
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(!isset($user_session->id))
            return $this->redirect()->toRoute('application', array('action' => 'index'));
        
    }


}
