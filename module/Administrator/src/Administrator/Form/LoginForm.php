<?php
namespace Administrator\Form;

use Zend\Captcha;
use Zend\Form\Element; 
use Zend\Form\Form; 

class LoginForm extends Form
{

    public function __construct()
    {
        parent::__construct('administrator');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
 
        $this->add(array( 
            'name' => 'email', 
            'type' => 'Zend\Form\Element\Email', 
            'attributes' => array( 
                'id' => 'email', 
                'class' => 'form-control',
                'placeholder' => 'example@email.com', 
                'required' => 'required',
            ), 
            'options' => array( 
                'label' => 'Email', 
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