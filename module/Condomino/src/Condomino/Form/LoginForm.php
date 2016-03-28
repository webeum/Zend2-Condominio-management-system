<?php
namespace Condomino\Form;

use Zend\Captcha;
use Zend\Form\Element; 
use Zend\Form\Form; 

class LoginForm extends Form
{

    public function __construct()
    {
        parent::__construct('condomino');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
 
        $this->add(array( 
            'name' => 'username', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'username', 
                'class' => 'form-control',
                'placeholder' => 'Inserisci il tuo codice fiscale...', 
                'required' => 'required',
            ), 
            'options' => array( 
                'label' => 'Codice fiscale', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'password', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'id' => 'password', 
                'class' => 'form-control',
                'placeholder' => 'Password...', 
                'required' => 'required', 
                'renderPassword' => 'true', 
            ), 
            'options' => array( 
                'label' => 'Password', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'remember', 
            'type' => 'Zend\Form\Element\Checkbox', 
            'attributes' => array( 
                'id' => 'remember', 
                'class' => 'checkbox',
            ), 
            'options' => array( 
                'label' => 'Ricordami', 
                'label_attributes' => array('class' => 'control-label color-label'),
            ), 
        )); 
       
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Login',
                'id' => 'submitbutton',
                'class' => 'btn',
            ),
        ));
    }
    
}