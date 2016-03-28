<?php
namespace Supplier\Model;

use Zend\Db\TableGateway\TableGateway;
use Common\Utils;

class SupplierTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchAllAllowed($condominiums)
    {
        $utils = new Utils();

        $filters = $utils->prepareFilters($condominiums);

        $select = $this->tableGateway->getSql()->select(); 

        $where = new \Zend\Db\Sql\Where;
        
        $i = 0;
        foreach ($filters as $key => $f) 
        {
            if($i == 0)
            {
                $where->like('condominium', "%" . $f . "%");
            }
            else
            {
                $where->or->like('condominium', "%" . $f . "%");    
            }
            
            $i++;
        }
        $select->where($where);
        $select->order('firstname ASC');
        $result = $this->tableGateway->selectWith($select);
        
        return $result;
    }

    public function getSupplier($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) 
        {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveSupplier(Supplier $supplier)
    {
        $data = array(
            'firstname' => $supplier->firstname,
            'lastname'  => $supplier->lastname,
            'companyname'  => $supplier->companyname,
            'specialization_id'  => $supplier->specialization_id,
            'taxnumber'  => $supplier->taxnumber,
            'email'  => $supplier->email,
            'pec'  => $supplier->pec,
            'telephone'  => $supplier->telephone,
            'fax'  => $supplier->fax,
            'mobile'  => $supplier->mobile,
            'address'  => $supplier->address,
            'condominium' => \Zend\Json\Json::encode($supplier->condominium),
            'creationdate' => date_create($supplier->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($supplier->modifydate)->format('Y-m-d H:i:s')
        );

        $id = (int)$supplier->id;
        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getSupplier($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteSupplier($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function getSupplierByCondominium($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'firstname', 'lastname', 'companyname', 'taxnumber', 'email', 'pec', 'telephone', 'fax', 'mobile', 'address'));
        $select->join('specialization', 'specialization.id = supplier.specialization_id', array('specialization' => 'description'), 'inner');

        $select->where->like('condominium', '%"' . $condominium_id . '"%');
        $select->order('description ASC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

}