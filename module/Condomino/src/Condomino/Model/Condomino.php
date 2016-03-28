<?php
namespace Condomino\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Condomino implements InputFilterAwareInterface
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $pec;
    public $username;
    public $password;
    public $fiscalcode;
    public $enabled;
    public $language_id;
    public $condominium;
    public $unit_1;
    public $unit_2;
    public $unit_3;
    public $adviser;
    public $notice;
    public $request;
    public $bacheca;
    public $creationdate;
    public $modifydate;
    public $activationdate;
    public $lastaccessdate;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->firstname = (isset($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (isset($data['lastname'])) ? $data['lastname'] : null;
        $this->email  = (isset($data['email'])) ? $data['email'] : null;
        $this->pec  = (isset($data['pec'])) ? $data['pec'] : null;
        $this->username  = (isset($data['username'])) ? $data['username'] : null;
        $this->password  = (isset($data['password'])) ? $data['password'] : null;
        $this->fiscalcode  = (isset($data['fiscalcode'])) ? $data['fiscalcode'] : null;
        $this->enabled  = (isset($data['enabled'])) ? $data['enabled'] : null;
        $this->language_id  = (isset($data['language_id'])) ? $data['language_id'] : null;
        $this->condominium  = (isset($data['condominium'])) ? $data['condominium'] : null;
        $this->unit_1  = (isset($data['unit_1'])) ? $data['unit_1'] : null;
        $this->unit_2  = (isset($data['unit_2'])) ? $data['unit_2'] : null;
        $this->unit_3  = (isset($data['unit_3'])) ? $data['unit_3'] : null;
        $this->adviser  = (isset($data['adviser'])) ? $data['adviser'] : 'N';
        $this->notice  = (isset($data['notice'])) ? $data['notice'] : 'N';
        $this->request  = (isset($data['request'])) ? $data['request'] : 'N';
        $this->bacheca  = (isset($data['bacheca'])) ? $data['bacheca'] : 'N';
        $this->creationdate  = (isset($data['creationdate'])) ? date_create($data['creationdate'])->format('d-m-Y H:i:s') : null;
        $this->modifydate  = (isset($data['modifydate'])) ? date_create($data['modifydate'])->format('d-m-Y H:i:s') : null;
        $this->lastaccessdate  = (isset($data['lastaccessdate'])) ? date_create($data['lastaccessdate'])->format('d-m-Y H:i:s') : null;
        $this->activationdate  = (isset($data['activationdate'])) ? date_create($data['activationdate'])->format('d-m-Y H:i:s') : null;        
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
                'name' => 'username', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array( 
                    array ( 
                        'name' => 'StringLength', 
                        'options' => array( 
                            'encoding' => 'UTF-8', 
                            'max' => '20', 
                        ), 
                    ), 
                    array ( 
                        'name' => 'NotEmpty', 
                        'options' => array( 
                            'messages' => array( 
                                'isEmpty' => 'Fiscalcode is required', 
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
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'PEC address format is not invalid', 
                            ) 
                        ), 
                    ), 
                ), 
            ]));
     
            /*
            $inputFilter->add($factory->createInput([ 
                'name' => 'username', 
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
            */

            $inputFilter->add($factory->createInput([ 
                'name' => 'password', 
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
                            'max' => '50', 
                        ), 
                    ), 
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
                'name' => 'fiscalcode', 
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
                            'min' => '16', 
                            'max' => '16', 
                        ), 
                    ), 
                ), 
            ])); 
     
            /*
            $inputFilter->add($factory->createInput([ 
                'name' => 'enabled', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
            ])); 
            */

            /*
            $inputFilter->add($factory->createInput([ 
                'name' => 'language_id', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
            ])); 
            */
            $inputFilter->add($factory->createInput([ 
                'name' => 'condominium', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_1', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_2', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_3', 
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
                            'max' => '1000', 
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

    public function getEditInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
            
        
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
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'PEC address format is not invalid', 
                            ) 
                        ), 
                    ), 
                ), 
            ]));
     
           
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'fiscalcode', 
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
                            'min' => '16', 
                            'max' => '16', 
                        ), 
                    ), 
                ), 
            ])); 
     
            /*
            $inputFilter->add($factory->createInput([ 
                'name' => 'enabled', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
            ])); 
            */

            /*
            $inputFilter->add($factory->createInput([ 
                'name' => 'language_id', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
            ])); 
            */
            $inputFilter->add($factory->createInput([ 
                'name' => 'condominium', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_1', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_2', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_3', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    }

    public function getRegisterFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
            
        
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

            $inputFilter->add($factory->createInput([ 
                'name' => 'password', 
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
                            'max' => '50', 
                        ), 
                    ), 
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
                'name' => 'fiscalcode', 
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
                            'min' => '16', 
                            'max' => '16', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'privacy', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
            ])); 
        
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    }

    public function getUpdateInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
            
        
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
                    array ( 
                        'name' => 'EmailAddress', 
                        'options' => array( 
                            'messages' => array( 
                                'emailAddressInvalidFormat' => 'PEC address format is not invalid', 
                            ) 
                        ), 
                    ), 
                ), 
            ]));
     
           
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'fiscalcode', 
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
                            'min' => '16', 
                            'max' => '16', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_1', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_2', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));

            $inputFilter->add($factory->createInput([ 
                'name' => 'unit_3', 
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
                            'max' => '1000', 
                        ), 
                    ), 
                ), 
            ]));
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    }

}
