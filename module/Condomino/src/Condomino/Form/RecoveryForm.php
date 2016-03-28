<?php
namespace Condomino\Form;

use Zend\Captcha;
use Zend\Form\Element; 
use Zend\Form\Form; 

class RecoveryForm extends Form
{

    public function __construct()
    {
        parent::__construct('condomino');

        $this->setAttribute('method', 'post');
 
        $this->add(array( 
            'name' => 'email', 
            'type' => 'Zend\Form\Element\Email', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'email', 
                'placeholder' => 'Email...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Email', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array(
            'name' => 'send',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Invia',
                'id' => 'submitbutton',
                'class' => 'btn',
            ),
        ));
    }
    
}