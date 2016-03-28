<?php
namespace Request\Form;

use Zend\Captcha;
use Zend\Form\Element; 
use Zend\Form\Form; 

class RequestForm extends Form
{

    public function __construct()
    {
        parent::__construct('request');

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
                'placeholder' => 'Inserisci la segnalazione...', 
                'required' => 'required', 
                'rows' => '10',
            ), 
            'options' => array( 
                'label' => 'Segnalazione', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'note', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'id' => 'note', 
                'class' => 'form-control jquery_ckeditor',
                'placeholder' => '', 
                'required' => 'required', 
                'rows' => '10',
            ), 
            'options' => array( 
                'label' => 'Nota amministratore', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'type_id', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'id' => 'type_id', 
                'class' => 'form-control',
                'required' => 'required', 
                'value' => 'I', 
            ), 
            'options' => array( 
                'label' => 'Tipologia', 
                'value_options' => array(
                    'I' => 'Richiesta informazioni', 
                    'P' => 'Segnalazione problema',
                    'D' => 'Segnalazione guasto' 
                ),
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'status_id', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'id' => 'status_id', 
                'class' => 'form-control',
                'value' => 'O', 
            ), 
            'options' => array( 
                'label' => 'Stato', 
                'value_options' => array(
                    'O' => 'Aperto', 
                    'C' => 'Chiuso' 
                ),
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
                'label_attributes' => array('class' => 'control-label color-label'),
                'format' => 'd-m-Y'
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
                'label_attributes' => array('class' => 'control-label color-label'),
                'format' => 'd-m-Y\TH:iP'
            ), 
        )); 

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Add',
                'id' => 'submitbutton',
                'class' => 'btn',
            ),
        ));
    }
    
}