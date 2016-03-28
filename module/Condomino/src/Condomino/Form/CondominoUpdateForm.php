<?php
namespace Condomino\Form;

use Zend\Captcha; 
use Zend\Form\Element; 
use Zend\Form\Form; 

class CondominoUpdateForm extends Form 
{ 

    public function __construct($condominiums) 
    { 
        parent::__construct('condomino'); 
        
        $this->setAttribute('method', 'post'); 

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        
        $this->add(array( 
            'name' => 'firstname', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'firstname', 
                'placeholder' => 'Nome...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Nome', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'lastname', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'lastname', 
                'placeholder' => 'Cognome...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Cognome', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
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
            'name' => 'pec', 
            'required' => 'false',
            'type' => 'Zend\Form\Element\Email', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'pec', 
                'placeholder' => 'PEC...', 
            ), 
            'options' => array( 
                'label' => 'PEC', 
                'label_attributes' => array('class' => 'control-label color-label')
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
            'name' => 'unit_1', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'id' => 'unit_1', 
                'class' => 'form-control',
                'placeholder' => '...', 
                'rows' => '2',
            ), 
            'options' => array( 
                'label' => 'Prima unità immobiliare', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'unit_2', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'id' => 'unit_2', 
                'class' => 'form-control',
                'placeholder' => '...', 
                'rows' => '2',
            ), 
            'options' => array( 
                'label' => 'Seconda unità immobiliare', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'unit_3', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'id' => 'unit_3', 
                'class' => 'form-control',
                'placeholder' => '...',  
                'rows' => '2',
            ), 
            'options' => array( 
                'label' => 'Terza unità immobiliare', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'notice', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'notice', 
                'required' => 'required', 
                'value' => 'N', 
            ), 
            'options' => array( 
                'label' => 'Ricevi email avvisi e scadenze', 
                'value_options' => array(
                    'N' => 'No', 
                    'Y' => 'Si', 
                ),
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'request', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'request', 
                'required' => 'required', 
                'value' => 'N', 
            ), 
            'options' => array( 
                'label' => 'Ricevi email di segnalazioni e guasti', 
                'value_options' => array(
                    'N' => 'No', 
                    'Y' => 'Si', 
                ),
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'bacheca', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'bacheca', 
                'required' => 'required', 
                'value' => 'N', 
            ), 
            'options' => array( 
                'label' => 'Ricevi email di messaggi in bacheca', 
                'value_options' => array(
                    'N' => 'No', 
                    'Y' => 'Si', 
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
            'name' => 'activationdate', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'activationdate', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Data di attivazione',
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'lastaccessdate', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'lastaccessdate', 
                'class' => 'form-control',
                'readonly' => 'true',
            ), 
            'options' => array( 
                'label' => 'Ultimo accesso',
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