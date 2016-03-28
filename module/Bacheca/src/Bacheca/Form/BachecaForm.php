<?php
namespace Bacheca\Form;

use Zend\Captcha;
use Zend\Form\Element; 
use Zend\Form\Form; 

class BachecaForm extends Form
{

    public function __construct()
    {
        parent::__construct('bacheca');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'condominium_id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'condomino_id',
            'type' => 'Hidden',
        ));

        $this->add(array( 
            'name' => 'object', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'object', 
                'class' => 'form-control',
                'placeholder' => 'Oggetto...', 
                'required' => 'required',                 
            ), 
            'options' => array( 
                'label' => 'Oggetto', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'message', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'id' => 'message', 
                'class' => 'form-control jquery_ckeditor',
                'placeholder' => 'Inserisci un messaggio...', 
                'required' => 'required', 
                'rows' => '8',
            ), 
            'options' => array( 
                'label' => 'Messaggio', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'creationdate', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'creationdate', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Data di creazione',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'modifydate', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'modifydate', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Data di modifica',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'add',
                'id' => 'submitbutton',
                'class' => 'btn',
            ),
        ));
    }
    
}