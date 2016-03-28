<?php
namespace Administrator\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Administrator implements InputFilterAwareInterface
{
    public $id;
    public $companyname;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $pec;
    public $language_id;
    public $enabled;
    public $telephone;
    public $mobile;
    public $fax;
    public $website;
    public $officehours;
    public $creationdate;
    public $modifydate;
    public $lastaccessdate;
    protected $inputFilter; 

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->companyname = (isset($data['companyname'])) ? $data['companyname'] : null;
        $this->firstname = (isset($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (isset($data['lastname'])) ? $data['lastname'] : null;
        $this->email  = (isset($data['email'])) ? $data['email'] : null;
        $this->password  = (isset($data['password'])) ? $data['password'] : null;
        $this->pec  = (isset($data['pec'])) ? $data['pec'] : null;
        $this->language_id  = (isset($data['language_id'])) ? $data['language_id'] : null;
        $this->enabled  = (isset($data['enabled'])) ? $data['enabled'] : null;
        $this->telephone  = (isset($data['telephone'])) ? $data['telephone'] : null;
        $this->mobile  = (isset($data['mobile'])) ? $data['mobile'] : null;
        $this->fax  = (isset($data['fax'])) ? $data['fax'] : null;
        $this->website = (isset($data['website'])) ? $data['website'] : null;
        $this->officehours  = (isset($data['officehours'])) ? $data['officehours'] : null;
        $this->creationdate  = (isset($data['creationdate'])) ? date_create($data['creationdate'])->format('d-m-Y H:i:s') : null;
        $this->modifydate  = (isset($data['modifydate'])) ? date_create($data['modifydate'])->format('d-m-Y H:i:s') : null;
        $this->lastaccessdate  = (isset($data['lastaccessdate'])) ? date_create($data['lastaccessdate'])->format('d-m-Y H:i:s') : null;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) 
    { 
        throw new \Exception("Not used"); 
    } 
    
    public function getInputLoginFilter()
    {
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
            
            $inputFilter->add($factory->createInput([ 
                'name' => 'email', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'max' => '255', 
                        ), 
                    ), 
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'Email address format is not invalid', 
                            ) 
                        ), 
                    ), 
                    array ( 
                        'name' => 'NotEmpty', 
                        'options' => array( 
                            'messages' => array( 
                                'isEmpty' => 'Email address is required', 
                            ) 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'password', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 

    public function getInputRecoveryFilter()
    {
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'email', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '255', 
                        ), 
                    ), 
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'Email address format is not invalid', 
                            ) 
                        ), 
                    ), 
                    array ( 
                        'name' => 'NotEmpty', 
                        'options' => array( 
                            'messages' => array( 
                                'isEmpty' => 'Email address is required', 
                            ) 
                        ), 
                    ), 
                ), 
            ])); 
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 
    

    public function getInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
       
            $inputFilter->add($factory->createInput([ 
                'name' => 'companyname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '200', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'firstname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'lastname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'email', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'max' => '255', 
                        ), 
                    ), 
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'Email address format is not invalid', 
                            ) 
                        ), 
                    ), 
                    array ( 
                        'name' => 'NotEmpty', 
                        'options' => array( 
                            'messages' => array( 
                                'isEmpty' => 'Email address is required', 
                            ) 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'password', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'password_verify', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'identical', 
                        'options' => array( 
                            'token' => 'password', 
                        ), 
                    ), 

                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'pec', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '0', 
                            'max' => '255', 
                        ), 
                    ),                     
                ), 
            ]));
            
            $inputFilter->add($factory->createInput([ 
                'name' => 'telephone', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'fax', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'mobile', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'language_id', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'enabled', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'website', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'officehours', 
                'required' => false, 
                'filters' => array( 
                    //array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 

    public function getEditInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
  
            $inputFilter->add($factory->createInput([ 
                'name' => 'companyname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '200', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'firstname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'lastname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'email', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'max' => '255', 
                        ), 
                    ), 
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'Email address format is not invalid', 
                            ) 
                        ), 
                    ), 
                    array ( 
                        'name' => 'NotEmpty', 
                        'options' => array( 
                            'messages' => array( 
                                'isEmpty' => 'Email address is required', 
                            ) 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'pec', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '0', 
                            'max' => '255', 
                        ), 
                    ),                     
                ), 
            ]));
            
            $inputFilter->add($factory->createInput([ 
                'name' => 'telephone', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'fax', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'mobile', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'language_id', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'enabled', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'website', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'officehours', 
                'required' => false, 
                'filters' => array( 
                    //array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 

    public function getRegisterInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'companyname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '200', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'firstname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'lastname', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'email', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'max' => '255', 
                        ), 
                    ), 
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'Email address format is not invalid', 
                            ) 
                        ), 
                    ), 
                    array ( 
                        'name' => 'NotEmpty', 
                        'options' => array( 
                            'messages' => array( 
                                'isEmpty' => 'Email address is required', 
                            ) 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'password', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'password_verify', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'identical', 
                        'options' => array( 
                            'token' => 'password', 
                        ), 
                    ), 

                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'telephone', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'fax', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'mobile', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'website', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '255', 
                        ), 
                    ),
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'officehours', 
                'required' => false, 
                'filters' => array( 
                    //array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'min' => '1', 
                            'max' => '400', 
                        ), 
                    ),
                ), 
            ])); 

            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 

    public function getPasswordInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
        
            $inputFilter->add($factory->createInput([ 
                'name' => 'old_password', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'new_password', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'password_verify', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'identical', 
                        'options' => array( 
                            'token' => 'new_password', 
                        ), 
                    ), 

                ), 
            ])); 

            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 


}
