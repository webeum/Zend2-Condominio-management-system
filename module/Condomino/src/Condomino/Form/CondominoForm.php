<?php
namespace Condomino\Form;

use Zend\Captcha; 
use Zend\Form\Element; 
use Zend\Form\Form; 

class CondominoForm extends Form 
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
 
        /*
        $this->add(array( 
            'name' => 'username', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'username', 
                'placeholder' => 'Username...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Username', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
        */

        $this->add(array( 
            'name' => 'password', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'password', 
                'placeholder' => 'Password...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Password', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'password_verify', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'password_verify', 
                'placeholder' => 'Conferma password...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Conferma password', 
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
            'name' => 'enabled', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'enabled', 
                'required' => 'required', 
                'value' => 'N', 
            ), 
            'options' => array( 
                'label' => 'Stato', 
                'value_options' => array(
                    'N' => 'Disabilitato', 
                    'Y' => 'Abilitato', 
                ),
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'language_id', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'language_id', 
                'required' => 'required', 
                'value' => 'IT',
            ), 
            'options' => array( 
                'label' => 'Lingua', 
                'value_options' => array(
                    'IT' => 'Italiano', 
                    'EN' => 'Inglese', 
                ),
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'condominium',
            'options' => array(
                'label' => 'Condomini di appartenenza',
                'value_options' => $condominiums,
                'label_attributes' => array('class' => 'control-label col-sm-4 color-label'),
            )
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
            'name' => 'adviser', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'adviser', 
                'required' => 'required', 
                'value' => 'N', 
            ), 
            'options' => array( 
                'label' => 'Consigliere', 
                'value_options' => array(
                    'N' => 'No', 
                    'Y' => 'Si', 
                ),
                'label_attributes' => array('class' => 'control-label color-label mandatory')
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

    public function populateValues($data)
    {   
        foreach($data as $key=>$row)
        {
           if (is_array(@json_decode($row))){
                $data[$key] =   new \ArrayObject(\Zend\Json\Json::decode($row), \ArrayObject::ARRAY_AS_PROPS);
           }
        } 
         
        parent::populateValues($data);
    }
        
}