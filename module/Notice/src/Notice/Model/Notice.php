<?php
namespace Notice\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Notice implements InputFilterAwareInterface
{
    public $id;
    public $author;
    public $object;
    public $message;
    public $condominium_id;
    public $administrator_id;
    public $creationdate;
    public $modifydate;
    protected $inputFilter; 

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->author = (isset($data['lastname'])) ? $data['firstname'] . ' ' . $data['lastname'] : "";
        $this->object = (isset($data['object'])) ? $data['object'] : null;        
        $this->message = (isset($data['message'])) ? $data['message'] : null;
        $this->condominium_id  = (isset($data['condominium_id'])) ? $data['condominium_id'] : null;
        $this->administrator_id  = (isset($data['administrator_id'])) ? $data['administrator_id'] : null;
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
                'name' => 'object', 
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
                'name' => 'message', 
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
                            'max' => '2000', 
                        ), 
                    ), 
                ), 
            ])); 
     
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 

}
