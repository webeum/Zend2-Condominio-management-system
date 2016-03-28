<?php
namespace Condomino\Form;

use Zend\Captcha;
use Zend\Form\Element; 
use Zend\Form\Form; 

class PasswordForm extends Form
{

    public function __construct()
    {
        parent::__construct('condomino');

        $this->setAttribute('method', 'post');
 
        $this->add(array( 
            'name' => 'old_password', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'old_password', 
                'class' => 'form-control',
                'required' => 'required',
            ), 
            'options' => array( 
                'label' => 'Password attuale', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'new_password', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'id' => 'new_password', 
                'class' => 'form-control',
                'required' => 'required', 
                'renderPassword' => 'true', 
            ), 
            'options' => array( 
                'label' => 'Nuova password', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'password_verify', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'id' => 'password_verify', 
                'class' => 'form-control',
                'required' => 'required', 
                'renderPassword' => 'true', 
            ), 
            'options' => array( 
                'label' => 'Verifica password', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        
        $this->add(array(
            'name' => 'change',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Salva',
                'id' => 'submitbutton',
                'class' => 'btn',
            ),
        ));
    }
    
}