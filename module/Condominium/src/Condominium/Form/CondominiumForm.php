<?php
namespace Condominium\Form;

use Zend\Captcha; 
use Zend\Form\Element; 
use Zend\Form\Form; 

class CondominiumForm extends Form 
{ 
    public function __construct($name = null) 
    { 
        parent::__construct('condominium'); 
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array( 
            'name' => 'name', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'name', 
                'placeholder' => 'Name...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Nome', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array(
            'name' => 'picture',
            'attributes' => array(
                'type'  => 'file',
                'id' => 'picture'
            ),
            'options' => array(
                'label' => 'Immagine (.jpeg, .jpg)',
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ),
        )); 
 
        $this->add(array( 
            'name' => 'address', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'class' => 'form-control jquery_ckeditor', 
                'id' => 'address', 
                'required' => 'required',
                'rows' => '4',
            ), 
            'options' => array( 
                'label' => 'Indirizzo', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'properties', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'properties', 
                'placeholder' => 'Unità immobiliari...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Unità immobiliari', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'tenants', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'tenants', 
                'placeholder' => 'Totale condomini...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Condomini', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'fiscalcode', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'fiscalcode', 
                'placeholder' => 'Codice fiscale...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Codice fiscale', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'fiscalperiodstart', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'fiscalperiodstart', 
                'placeholder' => 'Gestione...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Gestione', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'bankreference', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'class' => 'form-control jquery_ckeditor', 
                'id' => 'bankreference', 
                'required' => 'required',
                'rows' => '4', 
            ), 
            'options' => array( 
                'label' => 'Riferimenti bancari', 
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