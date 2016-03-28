<?php
namespace Condominium\Model;

use Zend\Db\TableGateway\TableGateway;

class CondominiumTable
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
        $select = $this->tableGateway->getSql()->select(); 
        //$select->where(array('id' => json_decode($condominiums, true)));
        $select->where(array('id' => $condominiums));
        $select->order('name ASC');
        $result = $this->tableGateway->selectWith($select);
        
        return $result;
    }

    public function getCondominium($id)
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

    public function saveCondominium(Condominium $condominium)
    {
        $data = array(
            'name' => $condominium->name,
            'address'  => $condominium->address,
            'fiscalcode'  => $condominium->fiscalcode,
            'fiscalperiodstart'  => $condominium->fiscalperiodstart,
            'bankreference'  => $condominium->bankreference,
            'administrator_id'  => $condominium->administrator_id,
            'properties'  => $condominium->properties,
            'tenants'  => $condominium->tenants,
            'creationdate' => date_create($condominium->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($condominium->modifydate)->format('Y-m-d H:i:s')
        );

        $id = (int)$condominium->id;
        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getCondominium($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCondominium($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    /*
    public function getCondominiumFiltered($condominiums)
    {
        $select = $this->tableGateway->getSql()->select(); 
        $select->columns(array('id', 'name'));        
        $select->where(array('id' => $condominiums));
        $select->order('name ASC');
        $result = $this->tableGateway->selectWith($select);

        $selectData = array();
 
        foreach ($result as $res) 
            $selectData[$res->id] = $res->name;
        
        return $selectData;
    }
    */   
     
    public function getCondominiumByUser($type, $id, $condominiums)
    {
        $selectData = array();
        $select = $this->tableGateway->getSql()->select(); 

        if($type == 'administrator')
        {
            $select->columns(array('id', 'name'));
            $select->where(array('administrator_id' => $id));
            $select->order('name ASC');
        }
        else
        {
            $select->columns(array('id', 'name'));
            $select->where(array('id' => $condominiums));
            $select->order('name ASC');
        }
        
        $result = $this->tableGateway->selectWith($select);

        foreach ($result as $res) 
            $selectData[$res->id] = $res->name;
        
        return $selectData;
    }

    public function getLastID()
    {
        $select = $this->tableGateway->getSql()->select(); 
        $select->columns(array('id')); 
        $select->order('id DESC')->limit(1);
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (!$row) 
        {
            throw new \Exception("Could not find lastid");
        }
        return $row->id;
    }

    public function getFirstID()
    {
        $select = $this->tableGateway->getSql()->select(); 
        $select->columns(array('id')); 
        $select->order('id ASC')->limit(1);
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();
        if (!$row) 
        {
            return null;
        }
        else
        {
            return $row->id;
        }
        
    }

}