<?php
namespace Supplier\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Supplier implements InputFilterAwareInterface
{
    public $id;
    public $firstname;
    public $lastname;
    public $companyname;
    public $specialization_id;
    public $specialization;
	public $taxnumber;
    public $email;
    public $pec;
    public $telephone;
    public $fax;
    public $mobile;
    public $address;
    public $condominium;
    public $creationdate;
    public $modifydate;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->firstname = (isset($data['firstname'])) ? $data['firstname'] : null;
        $this->lastname  = (isset($data['lastname'])) ? $data['lastname'] : null;
        $this->companyname  = (isset($data['companyname'])) ? $data['companyname'] : null;
        $this->specialization_id  = (isset($data['specialization_id'])) ? $data['specialization_id'] : null;
        $this->specialization  = (isset($data['specialization'])) ? $data['specialization'] : null;
        $this->taxnumber  = (isset($data['taxnumber'])) ? $data['taxnumber'] : null;
        $this->email  = (isset($data['email'])) ? $data['email'] : null;
        $this->pec  = (isset($data['pec'])) ? $data['pec'] : null;
        $this->telephone  = (isset($data['telephone'])) ? $data['telephone'] : null;
        $this->fax  = (isset($data['fax'])) ? $data['fax'] : null;
        $this->mobile  = (isset($data['mobile'])) ? $data['mobile'] : null;
        $this->address  = (isset($data['address'])) ? $data['address'] : null;
        $this->condominium  = (isset($data['condominium'])) ? $data['condominium'] : null;
        $this->creationdate  = (isset($data['creationdate'])) ? date_create($data['creationdate'])->format('d-m-Y H:i:s') : null;
        $this->modifydate  = (isset($data['modifydate'])) ? date_create($data['modifydate'])->format('d-m-Y H:i:s') : null;
    }



    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) 
    { 
        throw new \Exception("Not used"); 
    } 

    public function getInputFilter() 
    { 
        if (!$this->inputFilter) 
        { 
            $inputFilter = new InputFilter(); 
            $factory = new InputFactory(); 
            
            $inputFilter->add($factory->createInput([ 
                'name' => 'firstname', 
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
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'lastname', 
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
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 
     
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
                            'max' => '100', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'specialization_id', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'taxnumber', 
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
                            'min' => '11', 
                            'max' => '11', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'email',
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
                    /*
                    array ( 
                        'name' => 'NotEmpty', 
                        'options' => array( 
                            'messages' => array( 
                                'isEmpty' => 'Email address is required', 
                            ) 
                        ), 
                    ),
                    */
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
                'name' => 'address', 
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
                            'max' => '255', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'condominium', 
                'required' => false, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 

}