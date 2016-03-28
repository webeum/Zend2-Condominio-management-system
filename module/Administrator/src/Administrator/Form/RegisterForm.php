<?php
namespace Administrator\Form;

use Zend\Captcha;
use Zend\Form\Element; 
use Zend\Form\Form; 

class RegisterForm extends Form
{

    public function __construct($condominiums)
    {
        parent::__construct('administrator');

        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array( 
            'name' => 'companyname', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'companyname', 
                'class' => 'form-control',
                'placeholder' => 'Ragione sociale...', 
                'required' => 'required', 
            ), 
            'options' => array( 
                'label' => 'Ragione sociale', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'firstname', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'firstname', 
                'class' => 'form-control',
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
                'id' => 'lastname', 
                'class' => 'form-control',
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
            'name' => 'password_verify', 
            'type' => 'Zend\Form\Element\Password', 
            'attributes' => array( 
                'id' => 'password_verify', 
                'class' => 'form-control',
                'placeholder' => 'Verifica password...', 
                'required' => 'required', 
                'renderPassword' => 'true', 
            ), 
            'options' => array( 
                'label' => 'Verifica password', 
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
            'name' => 'language_id', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'id' => 'language_id', 
                'class' => 'form-control',
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
        */
        /*
        $this->add(array( 
            'name' => 'enabled', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'id' => 'enabled', 
                'class' => 'form-control',
                'required' => 'required', 
                'value' => 'N',
            ), 
            'options' => array( 
                'label' => 'Status', 
                'value_options' => array(
                    'N' => 'Disabled', 
                    'Y' => 'Enabled', 
                ),
            ), 
        )); 
        */
 
        $this->add(array( 
            'name' => 'telephone', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'telephone', 
                'class' => 'form-control',
                'placeholder' => '02.1234567', 
                'required' => 'required',
            ), 
            'options' => array( 
                'label' => 'Telefono', 
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 

        $this->add(array( 
            'name' => 'fax', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'fax', 
                'class' => 'form-control',
                'placeholder' => '02.1234567', 
            ), 
            'options' => array( 
                'label' => 'Fax', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'mobile', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'mobile', 
                'class' => 'form-control',
                'placeholder' => '392.1234567', 
            ), 
            'options' => array( 
                'label' => 'Cellulare', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 


        $this->add(array( 
            'name' => 'website', 
            'type' => 'Zend\Form\Element\Url', 
            'attributes' => array( 
                'id' => 'website', 
                'class' => 'form-control',
                'placeholder' => 'http://www.test.com', 
            ), 
            'options' => array( 
                'label' => 'Sito web', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'officehours', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'id' => 'officehours', 
                'class' => 'form-control jquery_ckeditor',
            ), 
            'options' => array( 
                'label' => 'Orario d\'ufficio',
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