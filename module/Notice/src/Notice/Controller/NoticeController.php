<?php
namespace Notice\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Notice\Form\NoticeForm; 
use Notice\Form\NoticeFormValidator; 
use Notice\Model\Notice;
use Common\Email;
use Common\Security;

class NoticeController extends AbstractActionController
{
    protected $noticeTable;

    public function getNoticeTable()
    {
        if (!$this->noticeTable) 
        {
            $sm = $this->getServiceLocator();
            $this->noticeTable = $sm->get('Notice\Model\NoticeTable');
        }
        return $this->noticeTable;
    }

    public function indexAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');
        
        return new ViewModel(array(
            'messages' => $this->getNoticeTable()->getNoticeMessages($user_session->condominium_id),
        ));
    }

    public function addAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form = new NoticeForm();
        $form->get('submit')->setValue('pubblica');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $notice = new Notice();
            $form->setInputFilter($notice->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $user_session = new Container('user');

                $notice->exchangeArray($form->getData());
                $notice->condominium_id = $user_session->condominium_id;
                $notice->administrator_id = $user_session->id;
                $notice->creationdate = date('Y-m-d H:i:s');
                $notice->modifydate = date('Y-m-d H:i:s');
                $this->getNoticeTable()->saveNotice($notice);

                // Redirect to messages list
                return $this->redirect()->toRoute('notice', array('action' => 'index'));
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
            return $this->redirect()->toRoute('notice', array('action' => 'add'));
        }

        $user_session = new Container('user');

        /*@@@ privileges check access @@@*/
        if($user_session->type == 'condomino' && !$this->checkPrivileges($id, $user_session->id))
            throw new \Exception("Access denied");
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/

        try 
        {
            $notice = $this->getNoticeTable()->getNotice($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('notice', array('action' => 'index'));
        }

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $form  = new NoticeForm();
        $form->bind($notice);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $form->setInputFilter($notice->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $notice = $form->getData();
                $notice->modifydate = date('Y-m-d H:i:s');
                $this->getNoticeTable()->saveNotice($notice);

                // Redirect to messages list
                return $this->redirect()->toRoute('notice', array('action' => 'index'));
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
            return $this->redirect()->toRoute('notice');
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
                $this->getNoticeTable()->deleteNotice($id);
            }

            // Redirect to messages list
            return $this->redirect()->toRoute('notice', array('action' => 'index'));
        }

        return array(
            'id'    => $id,
            'notice' => $this->getNoticeTable()->getNotice($id)
        );
    }

    public function sendAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
                
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) 
        {
            return $this->redirect()->toRoute('notice');
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
                $condominos = $this->getCondominoForNotice();

                $notice = $this->getNoticeTable()->getNotice($id);

                $sendEmail = new Email();  

                // send request message to administrator
                $sendEmail->sendNotice('Amministratore', '', 'info@exeimmobiliare.it', $notice->object, $notice->message);             

                foreach ($condominos as $condomino) 
                {
                    // send notice message to condominos
                    $sendEmail->sendNotice($condomino->firstname, $condomino->lastname, $condomino->email, $notice->object, $notice->message);
                } 

                // Redirect to list of send confirm
                return $this->redirect()->toRoute('notice', array('action' => 'noticesent'));
            }
            else
            {
                // Redirect to list of send confirm
                return $this->redirect()->toRoute('notice', array('action' => 'index'));               
            }
        }

        return array(
            'id'    => $id,
            'notice' => $this->getNoticeTable()->getNotice($id)
        );
    }

    public function noticesentAction()
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

    private function getCondominoForNotice()
    {
        $sm = $this->getServiceLocator();
        $condominoTable = $sm->get('Condomino\Model\CondominoTable');

        $user_session = new Container('user');
        
        return  $condominoTable->getCondominoForNotice($user_session->condominium_id);    
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(!isset($user_session->id))
            return $this->redirect()->toRoute('application', array('action' => 'index'));
        
    }

    private function checkPrivileges($id, $administrator_id)
    {
        return $this->getNoticeTable()->checkPrivileges($id, $administrator_id);
    }


}
