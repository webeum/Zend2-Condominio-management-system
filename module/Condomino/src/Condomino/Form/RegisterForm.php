<?php
namespace Condomino\Form;

use Zend\Captcha; 
use Zend\Form\Element; 
use Zend\Form\Form; 

class RegisterForm extends Form 
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
                'placeholder' => 'Verifica password...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Verifica password', 
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
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'privacy',
            'attributes' => array( 
                 
                'id' => 'privacy', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => '', 
                'label_attributes' => array('class' => 'control-label')
            ), 
        ));

        /*
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
                'label' => 'Language', 
                'value_options' => array(
                    'IT' => 'Italian', 
                    'EN' => 'English', 
                ),
            ), 
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'condominium',
            'attributes' => array(
                'options' => array(
                    'label' => 'Condominio di appartenenza',
                    'value_options' => $condominiums,
                    //'options' => $condominiums,
                    'label_attributes' => array('class' => 'control-label col-sm-4 color-label'),
                ),
                'value' => 1 //set selected to "public"
            )
        ));
        */

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
                'label' => 'Data attivazione',
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
                'value' => 'Register',
                'id' => 'submitbutton',
                'class' => 'btn',
            ),
        ));   

    } 
/*
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
*/        
}