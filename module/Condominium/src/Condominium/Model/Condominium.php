<?php
namespace Condominium\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Condominium implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $address;
    public $fiscalcode;
    public $fiscalperiodstart;
    public $bankreference;
    public $administrator_id;
    public $properties;
    public $tenants;
    public $creationdate;
    public $modifydate;
    protected $inputFilter; 

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->address  = (isset($data['address'])) ? $data['address'] : null;
        $this->fiscalcode  = (isset($data['fiscalcode'])) ? $data['fiscalcode'] : null;
        $this->fiscalperiodstart  = (isset($data['fiscalperiodstart'])) ? $data['fiscalperiodstart'] : null;
        $this->bankreference  = (isset($data['bankreference'])) ? $data['bankreference'] : null;
        $this->administrator_id  = (isset($data['administrator_id'])) ? $data['administrator_id'] : 0;
        $this->properties  = (isset($data['properties'])) ? $data['properties'] : 0;
        $this->tenants  = (isset($data['tenants'])) ? $data['tenants'] : null;
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
                'name' => 'name', 
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

            $inputFilter->add(
                $factory->createInput(array(
                  'name' => 'picture',
                  'required' => false,
                  'validators' => array(
                      new \Zend\Validator\File\UploadFile(),
                      new \Zend\Validator\File\Size(array('max' => 5242880 )), // 5120KB -> 5MB
                      new \Zend\Validator\File\Extension(
                        array('extension' => array('jpeg','jpg'))
                      )
                   )
                ))
            );

     
            $inputFilter->add($factory->createInput([ 
                'name' => 'address', 
                'required' => true, 
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
                            'min' => '1', 
                            'max' => '20', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'fiscalperiodstart', 
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
                            'max' => '50', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $inputFilter->add($factory->createInput([ 
                'name' => 'bankreference', 
                'required' => true, 
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

            $inputFilter->add($factory->createInput([ 
                'name' => 'properties', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array(
                    array(
                      'name' => 'Between',
                      'options' => array(
                          'min' => 1,
                          'max' => 99999,
                      ),
                    ),
                ),
            ]));      

            $inputFilter->add($factory->createInput([ 
                'name' => 'tenants', 
                'required' => true, 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ), 
                'validators' => array(
                    array(
                      'name' => 'Between',
                      'options' => array(
                          'min' => 1,
                          'max' => 99999,
                      ),
                    ),
                ),
            ]));    
 
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    }

}