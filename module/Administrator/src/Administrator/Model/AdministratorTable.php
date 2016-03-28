<?php
namespace Administrator\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Common\Utils;

class AdministratorTable
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

/*
    public function fetchAllAllowed($id, $condominiums)
    {
        $utils = new Utils();

        $filters = $utils->prepareFilters($condominiums);

        $select = $this->tableGateway->getSql()->select(); 

        $where = new \Zend\Db\Sql\Where;

        $having = new \Zend\Db\Sql\Having();
        $having->equalTo( 'administrator_ref_id', $id);

        if($id == 1) // only for super administrator
        { 
            $having->or->equalTo( 'administrator_ref_id', '');
        }
        else
        {
            $having->or->equalTo( 'id', $id);   
        }

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
        $select->having($having);
        $select->order('id ASC');
        $result = $this->tableGateway->selectWith($select);
        
        return $result;
    }
*/
    public function getAdministrator($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getAdministratorByCredentials($email, $password)
    {
        $rowset = $this->tableGateway->select(array('email' => $email, 'password' => $password, 'enabled' => 'Y'));
        return $rowset->current();
    }

    public function getAdministratorByEmail($email)
    {
        $rowset = $this->tableGateway->select(array('email' => $email, 'enabled' => 'Y'));
        return $rowset->current();
    }

    public function saveAdministrator(Administrator $administrator)
    {
        $id = (int)$administrator->id;

        $data = array(
            'companyname' => $administrator->companyname,
            'firstname' => $administrator->firstname,
            'lastname'  => $administrator->lastname,
            'email'  => $administrator->email,
            'password'  => $administrator->password,
            'pec'  => $administrator->pec,
            'language_id'  => $administrator->language_id,
            'enabled'  => $administrator->enabled,
            'telephone'  => $administrator->telephone,
            'mobile'  => $administrator->mobile,
            'fax'  => $administrator->fax,
            'website'  => $administrator->website,
            'officehours'  => $administrator->officehours,
            'creationdate' => date_create($administrator->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($administrator->modifydate)->format('Y-m-d H:i:s'),
            'lastaccessdate'  => date_create($administrator->lastaccessdate)->format('Y-m-d H:i:s')
        );

        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getAdministrator($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAdministrator($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function getAdministratorByCondominium($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'companyname', 'firstname', 'lastname', 'email', 'pec', 'telephone', 'fax', 'mobile', 'website', 'officehours'));
        $select->join('condominium', 'condominium.administrator_id = administrator.id');
        $select->where->equalTo('condominium.id', $condominium_id);
        $select->order('companyname ASC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

       
}