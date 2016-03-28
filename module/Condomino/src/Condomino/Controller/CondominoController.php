<?php
namespace Condomino\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Condomino\Form\CondominoForm; 
use Condomino\Form\LoginForm; 
use Condomino\Form\RegisterForm; 
use Condomino\Form\RecoveryForm; 
use Condomino\Form\PasswordForm; 
use Condomino\Form\CondominoUpdateForm; 
use Condomino\Form\CondominoFormValidator; 
use Condomino\Model\Condomino;
use Condominium\Model\Condominium;
use Common\Security;
use Common\Email;

class CondominoController extends AbstractActionController
{
    protected $condominoTable;

    public function getCondominoTable()
    {
        if (!$this->condominoTable) {
            $sm = $this->getServiceLocator();
            $this->condominoTable = $sm->get('Condomino\Model\CondominoTable');
        }
        return $this->condominoTable;
    }

    public function homeAction()
    {
        $this->verifyCookie();
        $this->checkSession();

        $condominiums = $this->getAvailableCondominium();
        $this->layout()->setVariable('condominiums', $condominiums);

        $user_session = new Container('user');
        if(isset($_POST['current_condominium'])) 
        {
            $user_session->condominium_id = $_POST['current_condominium'];
        }
        else
        {
            if(!isset($user_session->condominium_id))
            {
                foreach($condominiums as $key => $value)
                {
                    $user_session->condominium_id = $key;
                    break;
                }
            }
        }
        return $this->redirect()->toRoute('bacheca', array(
                    'action' => 'index'
                ));
        /*
        return new ViewModel(
            array(
                'messages' => $this->getBachecaMessages(), 
                'condominium'=> $this->getCondominium()
                )
        );
        */
    }

    public function loginfailedAction()
    {
        
    }

    public function invalidemailAction()
    {
        
    }

    public function emailerrorAction()
    {
        
    }

    public function invalidpasswordAction()
    {
        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
    }

    public function recoverysentAction()
    {
        
    }

    public function confirmAction()
    {
        
    }

    public function indexAction()
    {
        $this->verifyCookie();
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');

        return new ViewModel(array('condominos' => $this->getCondominoTable()->fetchAllAllowed($user_session->condominium_id)));
    }

    public function validateAction()
    {
        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');

        return new ViewModel(array('condominos' => $this->getCondominoTable()->fetchAllToValidate()));
    }

    public function viewAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');
        
        return new ViewModel(array(
            'condomino' => $this->getCondominoTable()->getCondomino($user_session->id),
        ));
    }

    public function addAction()
    {
        $this->checkSession();

        $condominiums = $this->getAvailableCondominium();

        $this->layout()->setVariable('condominiums', $condominiums);

        $form = new CondominoForm($condominiums);
        $form->get('submit')->setValue('inserisci');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $condomino = new Condomino();
            $form->setInputFilter($condomino->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $condomino->exchangeArray($form->getData());
                $condomino->creationdate = date('Y-m-d H:i:s');
                $condomino->modifydate = date('Y-m-d H:i:s');
                $condomino->activationdate = date('Y-m-d H:i:s');
                $condomino->lastaccessdate = date('Y-m-d H:i:s');
                $this->getCondominoTable()->saveCondomino($condomino);

                // Redirect to list of condominos
                return $this->redirect()->toRoute('condomino', array('action' => 'index'));
            }
        }
        return array('form' => $form);
    }

    public function registerAction()
    {
        $condominiums = $this->getAvailableCondominium();

        $form = new RegisterForm($condominiums);
        $form->get('submit')->setValue('registrati');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $condomino = new Condomino();
            $form->setInputFilter($condomino->getRegisterFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $condomino->exchangeArray($form->getData());
                $condomino->language_id = 'IT';
                $condomino->enabled = 'N';
                $condomino->pec = '';
                $condomino->unit_1 = '';
                $condomino->unit_2 = '';
                $condomino->unit_3 = '';
                $condomino->bacheca = 'Y';
                $condomino->notice = 'Y';
                $condomino->request = 'Y';
                $condomino->creationdate = date('Y-m-d H:i:s');
                $condomino->modifydate = date('Y-m-d H:i:s');
                $condomino->lastaccessdate = date('Y-m-d H:i:s');
                $this->getCondominoTable()->saveCondomino($condomino);

                $sendEmail = new Email();
                echo $sendEmail->userRegistration($condomino->firstname, $condomino->lastname, $condomino->email);

                // Redirect to confirm page
                return $this->redirect()->toRoute('condomino', array('action' => 'confirm'));
            }
        }
        return array('form' => $form);
    }

    public function loginAction()
    {
        $form = new LoginForm();
        $form->get('submit')->setValue('login');

        $this->verifyCookie();

        $user_session = new Container('user');

        if(isset($user_session->id))
            return $this->redirect()->toRoute('condomino', array('action' => 'home'));

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $condomino = new Condomino();
            $form->setInputFilter($condomino->getInputLoginFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $username = htmlspecialchars($request->getPost('username'));
                $password = htmlspecialchars($request->getPost('password'));
                $remember = htmlspecialchars($request->getPost('remember'));

                if($remember)
                {
                    setcookie("exe-u", base64_encode($username), time() + 36000 * 24, '/');
                    setcookie("exe-p", base64_encode($password), time() + 36000 * 24, '/');
                    setcookie("exe-t", base64_encode('user'), time() + 36000 * 24, '/');
                }

                // check credentials
                $condomino = $this->getCondominoTable()->getCondominoByCredentials($username, $password);

                if($condomino)
                {
                    $this->setUser($condomino);

                    // redirect to condomino homepage
                    return $this->redirect()->toRoute('condomino', array('action' => 'home'));
                }
                else
                {   
                    // redirect to login failed
                    return $this->redirect()->toRoute('condomino', array(
                        'controller' => 'condomino',
                        'action' =>  'loginfailed'
                    ));
                }
                
            }
        }
        return array('form' => $form);
    }

    public function recoveryAction()
    {
        $form = new RecoveryForm();
        $form->get('send')->setValue('invia');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $condomino = new Condomino();
            $form->setInputFilter($condomino->getInputRecoveryFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $email = htmlspecialchars($request->getPost('email'));

                // check credentials
                $condomino = $this->getCondominoTable()->getCondominoByEmail($email);

                if($condomino)
                {
                    // send email
                    $sendEmail = new Email();
                    if($sendEmail->sendPassword($email, $condomino->password))
                    {
                        return $this->redirect()->toRoute('condomino', array('action' => 'recoverysent'));
                    }
                    else
                    {
                        return $this->redirect()->toRoute('condomino', array('action' => 'emailerror'));    
                    } 
                      
                }
                else
                {   
                    // redirect to login failed
                    return $this->redirect()->toRoute('condomino', array(
                        'controller' => 'condomino',
                        'action' =>  'invalidemail'
                    ));
                }
                
            }
        }
        return array('form' => $form);
    }

    public function logoutAction()
    {
        # clear session variables
        $user_session = new Container('user');
        $user_session->getManager()->getStorage()->clear();

        # clear cookies
        unset($_COOKIE['exe-u']);
        unset($_COOKIE['exe-p']);
        unset($_COOKIE['exe-t']);
        $cookie_u = setcookie('exe-u', '', time() - 3600, '/');
        $cookie_p = setcookie('exe-p', '', time() - 3600, '/');
        $cookie_t = setcookie('exe-t', '', time() - 3600, '/');

        return $this->redirect()->toRoute('condomino', array(
            'controller' => 'condomino',
            'action' =>  'login'
        ));
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) 
        {
            return $this->redirect()->toRoute('condomino', array(
                'action' => 'add'
            ));
        }

        try 
        {
            $condomino = $this->getCondominoTable()->getCondomino($id);
            // store field not managed in form
            $password = $condomino->password;
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('condomino', array(
                'action' => 'index'
            ));
        }

        $enabledBeforeSave = $condomino->enabled;

        $condominiums = $this->getAvailableCondominium();
        $this->layout()->setVariable('condominiums', $condominiums);
        
        $form  = new CondominoForm($condominiums);
        $form->bind($condomino);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $form->setInputFilter($condomino->getEditInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $condomino = $form->getData();
                $condomino->password = $password;
                $condomino->modifydate = date('Y-m-d H:i:s');
                if($enabledBeforeSave == 'N' && $condomino->enabled == 'Y')
                {
                    $condomino->activationdate = date('Y-m-d H:i:s');
                    $sendEmail = new Email();
                    $sendEmail->confirmActivation($condomino->email);
                }
                $this->getCondominoTable()->saveCondomino($condomino);

                $user_session = new Container('user');
                if($user_session->type == 'administrator')
                {
                    // Redirect to list of condominos
                    return $this->redirect()->toRoute('condomino', array('action' => 'index'));
                }
                else
                {
                    // Redirect to list of condominos
                    return $this->redirect()->toRoute('condomino', array('action' => 'view'));
                }

            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function updateAction()
    {
        $user_session = new Container('user');

        $id = $user_session->id;

        try 
        {
            $condomino = $this->getCondominoTable()->getCondomino($id);
            // store field not managed in form
            $password = $condomino->password;
            $language = $condomino->language_id;
            $enabled = $condomino->enabled;
            $adviser = $condomino->adviser;
            $condominium = $condomino->condominium;
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('condomino', array('action' => 'index'));
        }

        $condominiums = $this->getAvailableCondominium();
        $this->layout()->setVariable('condominiums', $condominiums);
        
        $form  = new CondominoUpdateForm($condominiums);
        $form->bind($condomino);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();
        
        if ($request->isPost()) 
        {
            $form->setInputFilter($condomino->getUpdateInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $condomino = $form->getData();
                $condomino->password = $password;
                $condomino->language_id = $language;
                $condomino->enabled = $enabled;
                $condomino->adviser = $adviser;
                $condomino->condominium = \Zend\Json\Json::decode($condominium);
                $condomino->modifydate = date('Y-m-d H:i:s');
/*
                echo $condomino->password . '<br />';
                echo $condomino->language_id . '<br />';
                echo $condomino->enabled . '<br />';
                echo $condomino->condominium . '<br />';
*/
                $this->getCondominoTable()->saveCondomino($condomino);

                $user_session = new Container('user');
                if($user_session->type == 'administrator')
                {
                    // Redirect to list of condominos
                    return $this->redirect()->toRoute('condomino', array('action' => 'index'));
                }
                else
                {
                    // Redirect to list of condominos
                    return $this->redirect()->toRoute('condomino', array('action' => 'view'));
                }

            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function passwordAction()
    {
        $this->checkSession();        

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());


        $form = new PasswordForm();
        $form->get('change')->setValue('salva');

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $condomino = new Condomino();
            $form->setInputFilter($condomino->getPasswordInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $old_password = htmlspecialchars($request->getPost('old_password'));
                $new_password = htmlspecialchars($request->getPost('new_password'));

                $user_session = new Container('user');
                $id = $user_session->id;

                // check credentials
                $condomino = $this->getCondominoTable()->getCondomino($id);

                if($condomino)
                {
                    if($condomino->password == $old_password)
                    {
                        $condomino->password = $new_password;
                        $condomino->condominium = \Zend\Json\Json::decode($condomino->condominium);
                        $condomino->modifydate = date('Y-m-d H:i:s');
                        $this->getCondominoTable()->saveCondomino($condomino);
                        return $this->redirect()->toRoute('condomino', array('action' => 'logout'));
                    }
                    else
                    {
                        return $this->redirect()->toRoute('condomino', array('action' => 'invalidpassword'));    
                    } 
                      
                }
            }
        }
        return array('form' => $form);
    }


    public function deleteAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
                
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('condomino');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getCondominoTable()->deleteCondomino($id);
            }

            // Redirect to list of condominos
            return $this->redirect()->toRoute('condomino', array('action' => 'index'));
        }

        return array(
            'id'    => $id,
            'condomino' => $this->getCondominoTable()->getCondomino($id)
        );
    }

    private function verifyCookie()
    {
        # check cookie
        if(count($_COOKIE) > 0) 
        {
            $username = '';
            $password = '';

            if(isset($_COOKIE["exe-u"])) 
                $username = base64_decode($_COOKIE["exe-u"]);

            if(isset($_COOKIE["exe-p"])) 
                $password = base64_decode($_COOKIE["exe-p"]);

            // check credentials
            $condomino = $this->getCondominoTable()->getCondominoByCredentials($username, $password);

            if($condomino)
            {
                $this->setUser($condomino);
            }
        }
    }

    private function setUser($condomino)
    {
        // imposto sessione
        $user_session = new Container('user');
        $user_session->type = 'condomino';
        $user_session->id = $condomino->id;
        $user_session->username = $condomino->firstname . ' ' . $condomino->lastname;
        $condomino->condominium = \Zend\Json\Json::decode($condomino->condominium);
        $user_session->condominiums = $condomino->condominium;

        // ultimo accesso
        $condomino->lastaccessdate = date('Y-m-d H:i:s');
        $this->getCondominoTable()->saveCondomino($condomino);
    }

    private function getAvailableCondominium()
    {
        $sm = $this->getServiceLocator();
        $condominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        
        $user_session = new Container('user');

        return  $condominiumTable->getCondominiumByUser($user_session->type, $user_session->id, $user_session->condominiums);    
    }

    private function getBachecaMessages()
    {
        $sm = $this->getServiceLocator();
        $getBachecaTable = $sm->get('Bacheca\Model\BachecaTable');
        
        $user_session = new Container('user');

        return  $getBachecaTable->getBachecaMessages($user_session->condominium_id);    
    }

    private function getCondominium()
    {
        $sm = $this->getServiceLocator();
        $getCondominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        
        $user_session = new Container('user');

        return  $getCondominiumTable->getCondominium($user_session->condominium_id);    
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(!isset($user_session->id))
            return $this->redirect()->toRoute('application', array('action' => 'index'));
        
    }

}
