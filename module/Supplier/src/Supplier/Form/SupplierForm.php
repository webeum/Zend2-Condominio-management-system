<?php
namespace Supplier\Form; 

use Zend\Captcha; 
use Zend\Form\Element; 
use Zend\Form\Form;

class SupplierForm extends Form
{ 
    
    public function __construct($condominiums)  
    { 

        parent::__construct('supplier'); 
        
        $this->setAttribute('method', 'post'); 

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array( 
            'name' => 'companyname', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'companyname', 
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
                'class' => 'form-control', 
                'id' => 'firstname', 
                'placeholder' => 'Nome...', 
            ), 
            'options' => array( 
                'label' => 'Nome', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'lastname', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'lastname', 
                'placeholder' => 'Cognome...', 
            ), 
            'options' => array( 
                'label' => 'Cognome', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 

        $this->add(array( 
            'name' => 'specialization_id', 
            'type' => 'Zend\Form\Element\Select', 
            'attributes' => array( 
                'id' => 'specialization_id', 
                'class' => 'form-control',
                'required' => 'required', 
                'value' => '01', 
            ), 
            'options' => array( 
                'label' => 'Specializzazione', 
                'value_options' => array(
                    '01' => 'Consulenza', 
                    '02' => 'Impresa di pulizie', 
                    '03' => 'Impresa edile', 
                    '04' => 'Manutenzione ascensore', 
                    '05' => 'Manutenzione riscaldamento', 
                    '06' => 'Fabbro', 
                    '07' => 'Pronto intervento', 
                    '08' => 'Idraulico', 
                    '09' => 'Elettricista', 
                    '10' => 'Custode', 
                    '11' => 'Manutenzione verde', 
                    '12' => 'Manutenzione antincendio', 
                    '13' => 'Spurghi', 
                    '14' => 'Disinfestazione', 
                    '15' => 'Manutenzione Fotovoltaico', 
                    '16' => 'Manutenzione cancello elettrico', 
                    '17' => 'Contabilizzazione consumi',
                ),
                'label_attributes' => array('class' => 'control-label color-label mandatory')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'taxnumber', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'taxnumber', 
                'placeholder' => 'Partita IVA...', 
            ), 
            'options' => array( 
                'label' => 'Partita IVA', 
                'label_attributes' => array('class' => 'control-label color-label')
            ), 
        )); 
 
        $this->add(array( 
            'name' => 'email', 
            'type' => 'Zend\Form\Element\Email', 
            'attributes' => array( 
                'class' => 'form-control', 
                'id' => 'email', 
                'placeholder' => 'Email...', 
            ), 
            'options' => array( 
                'label' => 'Email', 
                'label_attributes' => array('class' => 'control-label color-label')
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

        $this->add(array( 
            'name' => 'telephone', 
            'type' => 'Zend\Form\Element\Text', 
            'attributes' => array( 
                'id' => 'telephone', 
                'class' => 'form-control',
                'placeholder' => '02.1234567',
            ), 
            'options' => array( 
                'label' => 'Telefono', 
                'label_attributes' => array('class' => 'control-label color-label')
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
            'name' => 'address', 
            'type' => 'Zend\Form\Element\Textarea', 
            'attributes' => array( 
                'class' => 'form-control jquery_ckeditor', 
                'id' => 'address', 
                'placeholder' => 'Indirizzo...', 
            ), 
            'options' => array( 
                'label' => 'Indirizzo',
                'label_attributes' => array('class' => 'control-label color-label')
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