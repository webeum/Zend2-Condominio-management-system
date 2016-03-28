<?php
namespace Administrator\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Administrator\Form\AdministratorForm; 
use Administrator\Form\LoginForm; 
use Administrator\Form\RegisterForm; 
use Administrator\Form\RecoveryForm; 
use Administrator\Form\PasswordForm; 
use Administrator\Form\AdministratorFormValidator; 
use Administrator\Model\Administrator; 
use Common\Security;
use Common\Email;

class AdministratorController extends AbstractActionController
{
    protected $administratorTable;

    public function getAdministratorTable()
    {
        if (!$this->administratorTable) {
            $sm = $this->getServiceLocator();
            $this->administratorTable = $sm->get('Administrator\Model\AdministratorTable');
        }
        return $this->administratorTable;
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

        return new ViewModel(array('condominium'=> $this->getCondominium()));
    }

    public function loginfailedAction()
    {
        
    }

    public function invalidemailAction()
    {
        
    }

    public function invalidpasswordAction()
    {
        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());
    }

    public function emailerrorAction()
    {
        
    }

    public function recoverysentAction()
    {
        
    }

    public function indexAction()
    {

        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');

        return new ViewModel(array(
            'administrators' => $this->getAdministratorTable()->getAdministratorByCondominium($user_session->condominium_id),
        ));
    }

    public function addAction()
    {
        $this->checkSession();

        $user_session = new Container('user');

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $condominiums = $this->getAvailableCondominium();

        $form = new AdministratorForm($condominiums);
        $form->get('submit')->setValue('inserisci');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $administrator = new Administrator();
            $form->setInputFilter($administrator->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $administrator->exchangeArray($form->getData());
                $administrator->creationdate = date('Y-m-d H:i:s');
                $administrator->modifydate = date('Y-m-d H:i:s');
                $administrator->lastaccessdate = date('Y-m-d H:i:s');
                $this->getAdministratorTable()->saveAdministrator($administrator);

                // Redirect to list of administrators
                return $this->redirect()->toRoute('administrator', array(
                    'action' => 'index'
                ));
            }
        }
        return array('form' => $form);
    }

    public function registerAction()
    {

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $condominiums = $this->getAvailableCondominium();

        $form = new RegisterForm($condominiums);
        $form->get('submit')->setValue('registrati');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $administrator = new Administrator();
            $form->setInputFilter($administrator->getRegisterInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $administrator->exchangeArray($form->getData());
                $administrator->language_id = 'IT';
                $administrator->enabled = 'Y';
                $administrator->creationdate = date('Y-m-d H:i:s');
                $administrator->modifydate = date('Y-m-d H:i:s');
                $administrator->lastaccessdate = date('Y-m-d H:i:s');
                $this->getAdministratorTable()->saveAdministrator($administrator);

                $user_session = new Container('user');
                $user_session->type = 'administrator';
                $user_session->id = $this->getAdministratorTable()->getAdministratorByCredentials($administrator->email, $administrator->password)->id;

                // Redirect to list administrator home
                return $this->redirect()->toRoute('administrator', array('action' => 'home'));
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
            return $this->redirect()->toRoute('administrator', array('action' => 'home'));
        

        $request = $this->getRequest();

        if ($request->isPost()) 
        {
            $administrator = new Administrator();
            $form->setInputFilter($administrator->getInputLoginFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $email = htmlspecialchars($request->getPost('email'));
                $password = htmlspecialchars($request->getPost('password'));
                $remember = htmlspecialchars($request->getPost('remember'));

                if($remember)
                {
                    setcookie("exe-u", base64_encode($email), time() + 36000 * 24, '/');
                    setcookie("exe-p", base64_encode($password), time() + 36000 * 24, '/');
                    setcookie("exe-t", base64_encode('administrator'), time() + 36000 * 24, '/');
                }

                // check credentials
                $administrator = $this->getAdministratorTable()->getAdministratorByCredentials($email, $password);

                if($administrator)
                {
                    $this->setAdministrator($administrator);

                    // redirect to condomino homepage
                    return $this->redirect()->toRoute('administrator', array('action' => 'home'));
                }
                else
                {   
                    // redirect to login failed
                    return $this->redirect()->toRoute('administrator', array(
                        'controller' => 'administrator',
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
            $administrator = new Administrator();
            $form->setInputFilter($administrator->getInputRecoveryFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $email = htmlspecialchars($request->getPost('email'));

                // check credentials
                $administrator = $this->getAdministratorTable()->getAdministratorByEmail($email);

                if($administrator)
                {
                    // send email
                    $sendEmail = new Email();
                    if($sendEmail->sendPassword($email, $administrator->password))
                    {
                        return $this->redirect()->toRoute('administrator', array('action' => 'recoverysent'));
                    }
                    else
                    {
                        return $this->redirect()->toRoute('administrator', array('action' => 'emailerror'));    
                    } 
                      
                }
                else
                {   
                    // redirect to login failed
                    return $this->redirect()->toRoute('administrator', array(
                        'controller' => 'administrator',
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

        return $this->redirect()->toRoute('administrator', array(
            'controller' => 'administrator',
            'action' =>  'login'
        ));
    }

    public function viewAction()
    {
        $this->checkSession();

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $user_session = new Container('user');
        
        return new ViewModel(array(
            'administrator' => $this->getAdministratorTable()->getAdministrator($user_session->id),
        ));
    }    

    public function editAction()
    {
        $this->checkSession();        

        $this->layout()->setVariable('condominiums', $this->getAvailableCondominium());

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) 
        {
            return $this->redirect()->toRoute('administrator', array(
                'action' => 'add'
            ));
        }

        try 
        {
            $administrator = $this->getAdministratorTable()->getAdministrator($id);
        }
        catch (\Exception $ex) 
        {
            return $this->redirect()->toRoute('administrator', array(
                'action' => 'index'
            ));
        }

        $password = $administrator->password;

        $condominiums = $this->getAvailableCondominium();

        $form  = new AdministratorForm($condominiums);
        $form->bind($administrator);
        $form->get('submit')->setAttribute('value', 'salva');

        $request = $this->getRequest();
        if ($request->isPost()) 
        {
            $form->setInputFilter($administrator->getEditInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $administrator = $form->getData();
                $administrator->password = $password;
                $administrator->modifydate = date('Y-m-d H:i:s');
                $this->getAdministratorTable()->saveAdministrator($administrator);

                // Redirect to list of administrators
                return $this->redirect()->toRoute('administrator', array(
                    'action' => 'view'
                ));
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
            $administrator = new Administrator();
            $form->setInputFilter($administrator->getPasswordInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) 
            {
                $old_password = htmlspecialchars($request->getPost('old_password'));
                $new_password = htmlspecialchars($request->getPost('new_password'));

                $user_session = new Container('user');
                $id = $user_session->id;

                // check credentials
                $administrator = $this->getAdministratorTable()->getAdministrator($id);

                if($administrator)
                {
                    if($administrator->password == $old_password)
                    {
                        $administrator->password = $new_password;
                        $administrator->modifydate = date('Y-m-d H:i:s');
                        $this->getAdministratorTable()->saveAdministrator($administrator);
                        return $this->redirect()->toRoute('administrator', array('action' => 'logout'));
                    }
                    else
                    {
                        return $this->redirect()->toRoute('administrator', array('action' => 'invalidpassword'));    
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
            return $this->redirect()->toRoute('administrator');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Si') {
                $id = (int) $request->getPost('id');
                $this->getAdministratorTable()->deleteAdministrator($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('administrator', array(
                'action' => 'index'
            ));
        }

        return array(
            'id'    => $id,
            'administrator' => $this->getAdministratorTable()->getAdministrator($id)
        );
    }

    private function verifyCookie()
    {
        # check cookie
        if(count($_COOKIE) > 0) 
        {
            $email = '';
            $password = '';

            if(isset($_COOKIE["exe-u"])) 
                $email = base64_decode($_COOKIE["exe-u"]);

            if(isset($_COOKIE["exe-p"])) 
                $password = base64_decode($_COOKIE["exe-p"]);

            // check credentials
            $administrator = $this->getAdministratorTable()->getAdministratorByCredentials($email, $password);

            if($administrator)
            {
                $this->setAdministrator($administrator);
            }
        }
    }

    private function setAdministrator($administrator)
    {
        // imposto sessione
        $user_session = new Container('user');
        $user_session->type = 'administrator';
        $user_session->id = $administrator->id;
        $user_session->username = $administrator->firstname . ' ' . $administrator->lastname;

        // ultimo accesso
        $administrator->lastaccessdate = date('Y-m-d H:i:s');
        $this->getAdministratorTable()->saveAdministrator($administrator);
    }

    private function getAvailableCondominium()
    {
        $sm = $this->getServiceLocator();
        $condominiumTable = $sm->get('Condominium\Model\CondominiumTable');
        
        $user_session = new Container('user');

        return  $condominiumTable->getCondominiumByUser($user_session->type, $user_session->id, $user_session->condominiums);    
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
