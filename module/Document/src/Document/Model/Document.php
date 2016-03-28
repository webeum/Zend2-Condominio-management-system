<?php
namespace Document\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Document implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $description;
    public $filename;
    public $mime;
    public $size;
    public $data;
    public $category_id;
    public $condominium_id;
    public $creationdate;
    public $modifydate;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->filename = (isset($data['filename'])) ? $data['filename'] : null;
        $this->mime  = (isset($data['mime'])) ? $data['mime'] : null;
        $this->size  = (isset($data['size'])) ? $data['size'] : null;
        $this->data  = (isset($data['data'])) ? $data['data'] : null;
        $this->category_id  = (isset($data['category_id'])) ? $data['category_id'] : null;
        $this->condominium_id  = (isset($data['condominium_id'])) ? $data['condominium_id'] : null;
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
                            'max' => '255', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'description', 
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

            $inputFilter->add(
                $factory->createInput(array(
                  'name' => 'data',
                  'required' => true,
                  'validators' => array(
                      new \Zend\Validator\File\UploadFile(),
                      new \Zend\Validator\File\Size(array('max' => 5242880 )), // 5120KB -> 5MB
                      new \Zend\Validator\File\Extension(
                        array('extension' => array('txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png', 'gif', 'jpeg', 'bmp', 'zip', 'rar'))
                      )
                   )
                ))
            );

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
                            'max' => '255', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'description', 
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