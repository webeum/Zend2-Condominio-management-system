<?php
namespace Bacheca\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Bacheca\Form\BachecaForm; 
use Bacheca\Form\BachecaFormValidator; 
use Bacheca\Model\Bacheca;
use Common\Email;
use Common\Security;

class BachecaController extends AbstractActionController
{
    protected $bachecaTable;

    public function getBachecaTable()
    {
        if (!$this->bachecaTable) {
            $sm = $this->getServiceLocator();
            $this->bachecaTable = $sm->get('Bacheca\Model\BachecaTable');
        }
        return $this->bachecaTable;
    }

    public function indexAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');
        
        return new ViewModel(array(
            'messages' => $this->getBachecaTable()->getBachecaMessages($user_session->condominium_id),
        ));
    }

    public function addAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form = new BachecaForm();
        $form->get('submit')->setValue('pubblica');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $bacheca = new Bacheca();
            $form->setInputFilter($bacheca->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $user_session = new Container('user');

                $bacheca->exchangeArray($form->getData());
                $bacheca->condominium_id = $user_session->condominium_id;
                $bacheca->condomino_id = $user_session->id;
                $bacheca->creationdate = date('Y-m-d H:i:s');
                $bacheca->modifydate = date('Y-m-d H:i:s');
                $this->getBachecaTable()->saveBacheca($bacheca);

                // Redirect to messages list
                return $this->redirect()->toRoute('bacheca', array(
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
            return $this->redirect()->toRoute('bacheca', array(
                'action' => 'add'
            ));
        }

        $user_session = new Container('user');

        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/

        try 
        {
            $bacheca = $this->getBachecaTable()->getBacheca($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('bacheca', array(
                'action' => 'index'
            ));
        }

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form  = new BachecaForm();
        $form->bind($bacheca);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $form->setInputFilter($bacheca->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $bacheca = $form->getData();
                $bacheca->modifydate = date('Y-m-d H:i:s');
                $this->getBachecaTable()->saveBacheca($bacheca);

                // Redirect to messages list
                return $this->redirect()->toRoute('bacheca', array(
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
            return $this->redirect()->toRoute('bacheca');
        }

        $user_session = new Container('user');
        
        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $request = $this->getRequest();

        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si')
            {
                $id = (int) $request->getPost('id');
                $this->getBachecaTable()->deleteBacheca($id);
            }

            // Redirect to messages list
            return $this->redirect()->toRoute('bacheca', array('action' => 'index'));
        }

        return array(
            'id'    => $id,
            'bacheca' => $this->getBachecaTable()->getBacheca($id)
        );
    }

    public function sendAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
                
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) 
        {
            return $this->redirect()->toRoute('bacheca');
        }

        $user_session = new Container('user');
        
        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        
        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $send = $request->getPost('send', 'No');

            if ($send == 'Si') 
            {
                $id = (int) $request->getPost('id');
                $condominos = $this->getCondominoForBacheca();

                $bacheca = $this->getBachecaTable()->getBacheca($id);

                $sendEmail = new Email();  

                // send request message to administrator
                $sendEmail->sendBacheca($bacheca->author, 'Amministratore', '', 'info@exeimmobiliare.it', $bacheca->object, $bacheca->message);             

                foreach ($condominos as $condomino) 
                {
                    // send bacheca message to condominos
                    $sendEmail->sendBacheca($bacheca->author, $condomino->firstname, $condomino->lastname, $condomino->email, $bacheca->object, $bacheca->message);
                } 

                // Redirect to list of send confirm
                return $this->redirect()->toRoute('bacheca', array('action' => 'bachecasent'));
            }
            else
            {
                // Redirect to list of send confirm
                return $this->redirect()->toRoute('bacheca', array('action' => 'index'));               
            }
        }

        return array(
            'id'    => $id,
            'bacheca' => $this->getBachecaTable()->getBacheca($id)
        );
    }

    public function bachecasentAction()
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

    private function getCondominoForBacheca()
    {
        $sm = $this->getServiceLocator();
        $condominoTable = $sm->get('Condomino\Model\CondominoTable');

        $user_session = new Container('user');
        
        return  $condominoTable->getCondominoForBacheca($user_session->condominium_id);    
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(!isset($user_session->id))
            return $this->redirect()->toRoute('application', array('action' => 'index'));
        
    }

    private function checkPrivileges($id, $condomino_id)
    {
        return $this->getBachecaTable()->checkPrivileges($id, $condomino_id);
    }


}
