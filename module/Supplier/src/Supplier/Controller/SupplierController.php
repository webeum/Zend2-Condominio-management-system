<?php
namespace Supplier\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Supplier\Form\SupplierForm; 
use Supplier\Form\SupplierFormValidator; 
use Supplier\Model\Supplier; 

class SupplierController extends AbstractActionController
{
    protected $supplierTable;

    public function getSupplierTable()
    {
        if (!$this->supplierTable) {
            $sm = $this->getServiceLocator();
            $this->supplierTable = $sm->get('Supplier\Model\SupplierTable');
        }
        return $this->supplierTable;
    }    

    public function indexAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');

        return new ViewModel(array(
            'suppliers' => $this->getSupplierTable()->getSupplierByCondominium($user_session->condominium_id),
        ));
    }

    public function addAction()
    {
        $this->checkSession();

        $condominiums = $this->getAvailableCondominium();
        $this->layout()->setVariable('condominiums', $condominiums);

        $form = new SupplierForm($condominiums);
        $form->get('submit')->setValue('inserisci');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $supplier = new Supplier();
            $form->setInputFilter($supplier->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $supplier->exchangeArray($form->getData());
                $supplier->creationdate = date('Y-m-d H:i:s');
                $supplier->modifydate = date('Y-m-d H:i:s');
                $this->getSupplierTable()->saveSupplier($supplier);

                // Redirect to list of suppliers
                return $this->redirect()->toRoute('supplier');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $this->checkSession();

        $condominiums = $this->getAvailableCondominium();
        $this->layout()->setVariable('condominiums', $condominiums);

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) 
        {
            return $this->redirect()->toRoute('supplier', array(
                'action' => 'add'
            ));
        }

        try 
        {
            $supplier = $this->getSupplierTable()->getSupplier($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('supplier', array(
                'action' => 'index'
            ));
        }
        
        $form = new SupplierForm($condominiums);
        $form->bind($supplier);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $form->setInputFilter($supplier->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $supplier = $form->getData();
                $supplier->modifydate = date('Y-m-d H:i:s');
                $this->getSupplierTable()->saveSupplier($supplier);

                // Redirect to list of suppliers
                return $this->redirect()->toRoute('supplier');
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
            return $this->redirect()->toRoute('supplier');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getSupplierTable()->deleteSupplier($id);
            }

            // Redirect to list of suppliers
            return $this->redirect()->toRoute('supplier');
        }

        return array(
            'id'    => $id,
            'supplier' => $this->getSupplierTable()->getSupplier($id)
        );
    }
    /*
    public function getCondominium()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $sql       = 'SELECT id, name FROM condominium ORDER BY name ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();
 
        $selectData = array();
 
        foreach ($result as $res) {
            $selectData[$res['id']] = $res['name'];
        }
 
        return $selectData;
    }
    */
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
