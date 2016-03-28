<?php
namespace Condomino\Model;

use Zend\Db\TableGateway\TableGateway;
use Common\Utils;

class CondominoTable
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

    public function fetchAllAllowed($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select(); 
        $where = new \Zend\Db\Sql\Where;
        $where->like('condominium', '%"' . $condominium_id . '"%');
        $select->where($where);
        $select->order('firstname ASC');
        $result = $this->tableGateway->selectWith($select);
        return $result;
    }

/*
    public function fetchAllAllowed($condominium_id)
    {
        $utils = new Utils();

        $filters = $utils->prepareFilters($condominiums);

        $select = $this->tableGateway->getSql()->select(); 

        $where = new \Zend\Db\Sql\Where;
        
        $i = 0;

        foreach ($condominiums as $key => $f) 
        {
            if($i == 0)
            {
                $where->like('condominium', "%" . $key . "%");
            }
            else
            {
                $where->or->like('condominium', "%" . $key . "%");    
            }
            
            $i++;
        }
        $select->where($where);
        $select->order('firstname ASC');
        $result = $this->tableGateway->selectWith($select);
        echo 'allowed';
        return $result;
    }
*/

    public function fetchAllToValidate()
    {
        $result = $this->tableGateway->select(array('enabled' => 'N'));
        return $result;
    }

    public function getCondomino($id)
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

    public function getCondominoByCredentials($username, $password)
    {
        $rowset = $this->tableGateway->select(array('username' => $username, 'password' => $password, 'enabled' => 'Y'));
        return $rowset->current();
    }

    public function getCondominoByEmail($email)
    {
        $rowset = $this->tableGateway->select(array('email' => $email, 'enabled' => 'Y'));
        return $rowset->current();
    }

    public function getCondominoForNotice($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select(); 
        
        $where = new \Zend\Db\Sql\Where;
        $where->like('condominium', '%"' . $condominium_id . '"%');
        $where->and->equalTo('notice', 'Y');
        $select->where($where);

        return $result = $this->tableGateway->selectWith($select);
    }

    public function getCondominoForRequest($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select(); 
        
        $where = new \Zend\Db\Sql\Where;
        $where->like('condominium', '%"' . $condominium_id . '"%');
        $where->and->equalTo('request', 'Y');
        $select->where($where);

        return $result = $this->tableGateway->selectWith($select);
    }

    public function getCondominoForBacheca($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select(); 
        
        $where = new \Zend\Db\Sql\Where;
        $where->like('condominium', '%"' . $condominium_id . '"%');
        $where->and->equalTo('bacheca', 'Y');
        $select->where($where);

        return $result = $this->tableGateway->selectWith($select);
    }

    public function saveCondomino(Condomino $condomino)
    {
        $data = array(
            'firstname' => $condomino->firstname,
            'lastname'  => $condomino->lastname,
            'email'  => $condomino->email,
            'pec'  => $condomino->pec,
            'username'  => $condomino->fiscalcode, // set fiscalcode as username
            'password'  => $condomino->password,
            'fiscalcode'  => $condomino->fiscalcode,
            'enabled'  => $condomino->enabled,
            'language_id'  => $condomino->language_id,
            'condominium' => \Zend\Json\Json::encode($condomino->condominium),
            'unit_1'  => $condomino->unit_1,
            'unit_2'  => $condomino->unit_2,
            'unit_3'  => $condomino->unit_3,
            'adviser'  => $condomino->adviser,
            'notice'  => $condomino->notice,
            'request'  => $condomino->request,
            'bacheca'  => $condomino->bacheca,
            'creationdate' => date_create($condomino->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($condomino->modifydate)->format('Y-m-d H:i:s'),
            'activationdate'  => date_create($condomino->activationdate)->format('Y-m-d H:i:s'),
            'lastaccessdate'  => date_create($condomino->lastaccessdate)->format('Y-m-d H:i:s')
        );

        $id = (int)$condomino->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getCondomino($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCondomino($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function getRegisteredCondomino($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select(); 
        
        $where = new \Zend\Db\Sql\Where;
        $where->like('condominium', '%"' . $condominium_id . '"%');
        $select->where($where);

        return $result = $this->tableGateway->selectWith($select);
    }

    public function getAdviserCondomino($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select(); 
        
        $where = new \Zend\Db\Sql\Where;
        $where->like('condominium', '%"' . $condominium_id . '"%');
        $where->and->equalTo('adviser', 'Y');
        $select->where($where);

        return $result = $this->tableGateway->selectWith($select);
    }    
}