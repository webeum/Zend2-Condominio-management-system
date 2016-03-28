<?php
namespace Request\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Request\Form\RequestForm; 
use Request\Form\RequestFormValidator; 
use Request\Model\Request;
use Common\Email;

class RequestController extends AbstractActionController
{
    protected $requestTable;

    public function getRequestTable()
    {
        if (!$this->requestTable) {
            $sm = $this->getServiceLocator();
            $this->requestTable = $sm->get('Request\Model\RequestTable');
        }
        return $this->requestTable;
    }

    public function indexAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');
        
        return new ViewModel(array(
            'requests' => $this->getRequestTable()->getRequests($user_session->condominium_id),
        ));
    }

    public function addAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form = new RequestForm();
        $form->get('submit')->setValue('inserisci');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $req = new Request();
            $form->setInputFilter($req->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $user_session = new Container('user');

                $req->exchangeArray($form->getData());
                $req->note = '';
                $req->condominium_id = $user_session->condominium_id;
                $req->condomino_id = $user_session->id;
                $req->status_id = 'O'; # open
                $req->creationdate = date('Y-m-d H:i:s');
                $req->modifydate = date('Y-m-d H:i:s');
                $this->getRequestTable()->saveRequest($req);

                // Redirect to requests list
                return $this->redirect()->toRoute('request', array(
                    'action' => 'index'
                ));
            }
        }
        return array('form' => $form);
    }

   
    public function editAction()
    {
        $this->checkSession();

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) 
        {
            return $this->redirect()->toRoute('request', array('action' => 'add'));
        }

        $user_session = new Container('user');

        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->condomino_id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/

        try 
        {
            $req = $this->getRequestTable()->getRequest($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('request', array(
                'action' => 'index'
            ));
        }

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form  = new RequestForm();
        $form->bind($req);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $form->setInputFilter($req->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $req = $form->getData();
                $req->modifydate = date('Y-m-d H:i:s');
                $this->getRequestTable()->saveRequest($req);

                // Redirect to requests list
                return $this->redirect()->toRoute('request', array(
                    'action' => 'index'
                ));
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
        
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id)
        {
            return $this->redirect()->toRoute('request');
        }

        $user_session = new Container('user');

        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->condomino_id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') 
            {
                $id = (int) $request->getPost('id');
                $this->getRequestTable()->deleteRequest($id);
            }

            // Redirect to requests list
            return $this->redirect()->toRoute('request', array(
                'action' => 'index'
            ));
        }

        return array(
            'id'    => $id,
            'request' => $this->getRequestTable()->getRequest($id)
        );
    }

    public function sendAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
                
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) 
        {
            return $this->redirect()->toRoute('request');
        }

        $user_session = new Container('user');

        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->condomino_id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/        

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $send = $request->getPost('send', 'No');

            if ($send == 'Si') 
            {
                $id = (int) $request->getPost('id');
                $condominos = $this->getCondominoForRequest();

                $request = $this->getRequestTable()->getRequest($id);

                $sendEmail = new Email();               

                // send request message to administrator
                $sendEmail->sendRequest('Amministratore', '', 'info@exeimmobiliare.it', $request->object, $request->message, $request->note);

                // send request message to condominos
                foreach ($condominos as $condomino) 
                {
                    $sendEmail->sendRequest($condomino->firstname, $condomino->lastname, $condomino->email, $request->object, $request->message, $request->note);
                } 

                // Redirect to list of send confirm
                return $this->redirect()->toRoute('request', array('action' => 'requestsent'));
            }
            else
            {
                // Redirect to list of send confirm
                return $this->redirect()->toRoute('request', array('action' => 'index'));               
            }
        }

        return array(
            'id'    => $id,
            'request' => $this->getRequestTable()->getRequest($id)
        );
    }

    public function requestsentAction()
    {
        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
    }

    private function getAvailableCondominium()
    {
        $sm = $this->getServiceLocator();
        $condominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        
        $user_session = new Container('user');

        return  $condominiumTable->getCondominiumByUser($user_session->type, $user_session->id, $user_session->condominiums);    
    }

    private function getCondominoForRequest()
    {
        $sm = $this->getServiceLocator();
        $condominoTable = $sm->get('Condomino\Model\CondominoTable');

        $user_session = new Container('user');
        
        return  $condominoTable->getCondominoForRequest($user_session->condominium_id);    
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(!isset($user_session->id))
            return $this->redirect()->toRoute('application', array('action' => 'index'));
        
    }

    private function checkPrivileges($id, $condomino_id)
    {
        return $this->getRequestTable()->checkPrivileges($id, $condomino_id);
    }

}
