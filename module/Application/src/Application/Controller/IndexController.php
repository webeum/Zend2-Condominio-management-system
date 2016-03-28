<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->verifyCookie();
    	$this->checkSession();
        //return $this->redirect()->toRoute('condomino', array('action' => 'login'));
        return new ViewModel();
    
    }

    private function verifyCookie()
    {
        # check cookie
        if(count($_COOKIE) > 0) 
        {
            $type = '';
            $user = '';
            $password = '';

            if(isset($_COOKIE["exe-t"]))
                $type = base64_decode($_COOKIE["exe-t"]);

            if(isset($_COOKIE["exe-u"])) 
                $user = base64_decode($_COOKIE["exe-u"]);

            if(isset($_COOKIE["exe-p"])) 
                $password = base64_decode($_COOKIE["exe-p"]);

/*
echo isset($_COOKIE["exe-t"]);
echo '2';
echo $type;
echo $user;
echo $password;
*/
            if(isset($type))
            {
                $sm = $this->getServiceLocator();

                switch ($type) 
                {
                    case 'administrator':

                        $administratorTable = $sm->get('Administrator\Model\AdministratorTable');
                        $administrator = $administratorTable->getAdministratorByCredentials($user, $password);

                        if($administrator)
                            $this->setAdministrator($administrator);
                        
                        break;
                    
                    case 'user':

                        $condominoTable = $sm->get('Condomino\Model\CondominoTable');
                        $condomino = $condominoTable->getCondominoByCredentials($user, $password);

                        if($condomino)
                            $this->setUser($condomino);

                        break;

                    default:
                        break;
                }
            }
        }
    }

    private function setAdministrator($administrator)
    {
        $sm = $this->getServiceLocator();
        $administratorTable = $sm->get('Administrator\Model\AdministratorTable');

        // imposto sessione
        $user_session = new Container('user');
        $user_session->type = 'administrator';
        $user_session->id = $administrator->id;
        $user_session->username = $administrator->firstname . ' ' . $administrator->lastname;

        // ultimo accesso
        $administrator->lastaccessdate = date('Y-m-d H:i:s');
        $administratorTable->saveAdministrator($administrator);
    }

    private function setUser($condomino)
    {
        $sm = $this->getServiceLocator();
        $condominoTable = $sm->get('Condomino\Model\CondominoTable');

        // imposto sessione
        $user_session = new Container('user');
        $user_session->type = 'condomino';
        $user_session->id = $condomino->id;
        $user_session->username = $condomino->firstname . ' ' . $condomino->lastname;
        $condomino->condominium = \Zend\Json\Json::decode($condomino->condominium);
        $user_session->condominiums = $condomino->condominium;

        // ultimo accesso
        $condomino->lastaccessdate = date('Y-m-d H:i:s');
        $condominoTable->saveCondomino($condomino);
    }

    private function checkSession()
    {
        $user_session = new Container('user');

        if(isset($user_session->id))
        {
        	if($user_session->type == 'administrator')
        	{
        		return $this->redirect()->toRoute('administrator', array('action' => 'home'));
        	}
        	else
        	{
				return $this->redirect()->toRoute('condomino', array('action' => 'home'));
        	}
        }
    }
}
