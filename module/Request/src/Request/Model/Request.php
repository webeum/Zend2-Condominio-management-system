<?php
namespace Request\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Request implements InputFilterAwareInterface
{
    public $id;
    public $author;
    public $object;
    public $message;
    public $note;
    public $condominium_id;
    public $condomino_id;
    public $status_id;
    public $status;
    public $type_id;
    public $type;
    public $creationdate;
    public $modifydate;
    protected $inputFilter; 

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->author = (isset($data['lastname'])) ? $data['firstname'] . ' ' . $data['lastname'] : "";
        $this->object = (isset($data['object'])) ? $data['object'] : null;
        $this->message = (isset($data['message'])) ? $data['message'] : null;
        $this->note = (isset($data['note'])) ? $data['note'] : null;
        $this->condominium_id  = (isset($data['condominium_id'])) ? $data['condominium_id'] : null;
        $this->condomino_id  = (isset($data['condomino_id'])) ? $data['condomino_id'] : null;
        $this->status_id  = (isset($data['status_id'])) ? $data['status_id'] : null;
        $this->status  = (isset($data['status_id'])) ? $this->setStatus($data['status_id']) : '';
        $this->type_id  = (isset($data['type_id'])) ? $data['type_id'] : null;
        $this->type  = (isset($data['type_id'])) ? $this->setType($data['type_id']) : '';
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
                            'max' => '4000', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'note', 
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
                            'max' => '4000', 
                        ), 
                    ), 
                ), 
            ])); 

            $inputFilter->add($factory->createInput([ 
                'name' => 'type_id', 
                'filters' => array( 
                    array('name' => 'StripTags'), 
                    array('name' => 'StringTrim'), 
                ) 
            ])); 
     
            $this->inputFilter = $inputFilter; 
        } 
        
        return $this->inputFilter; 
    } 

    private function setStatus($status_id)
    {
        switch ($status_id) {
            case 'O':
                $status = 'Aperto';
                break;
            case 'C':
                $status = 'Chiuso';
                break;
            default:
                $status = 'Aperto';
                break;
        }
        return $status;
    }

    private function setType($type_id)
    {
        switch ($type_id) {
            case 'I':
                $type = 'Richista informazioni';
                break;
            case 'D':
                $type = 'Segnalazione guasto';
                break;
            case 'P':
                $type = 'Segnalazione problema';
                break;
            default:
                $type = 'Richiesta informazioni';
                break;
        }
        return $type;
    }

}
