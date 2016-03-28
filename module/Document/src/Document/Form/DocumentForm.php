<?php
namespace Document\Form; 

use Zend\Captcha; 
use Zend\Form\Element; 
use Zend\Form\Form;

class DocumentForm extends Form
{ 
    
    public function __construct()  
    { 

        parent::__construct('document'); 
        
        $this->setAttribute('method', 'post'); 
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'category_id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'condominium_id',
            'type' => 'Hidden',
        ));
        
        $this->add(array( 
            'name' => 'name', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'name', 
                'placeholder' => 'Nome del file...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Nome',
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'description', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'description', 
                'placeholder' => 'Descrizione...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Descrizione',
                'label_attributes' => array('class' => 'control-label color-label mandatory') 
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'mime', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'mime', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Tipologia',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        ));  

        $this->add(array( 
            'name' => 'size', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'size', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Dimensione',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'filename', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'filename', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Filename',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'mime', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'mime', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Tipologia',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'size', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'size', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Dimensioni',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array(
            'name' => 'data',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File',
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
                'value' => 'Add',
                'id' => 'submitbutton',
                'class' => 'btn',
            ),
        ));       
    } 

} 