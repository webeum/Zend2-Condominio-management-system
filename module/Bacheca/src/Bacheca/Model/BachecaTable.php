<?php
namespace Bacheca\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Common\Utils;

class BachecaTable
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

    public function getBachecaMessages($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'object', 'message', 'condominium_id', 'condomino_id', 'creationdate', 'modifydate'));
        $select->join('condomino', 'condomino.id = bacheca.condomino_id', array('firstname', 'lastname'), 'inner');
        $select->where(array('condominium_id' => $condominium_id));
        $select->order('creationdate DESC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function checkPrivileges($id, $condomino_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('id' => $id), array('condomino_id' => $condomino_id));
        $rowset = $this->tableGateway->selectWith($select);
        $row = $rowset->current();

        if (!$row) 
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function getBacheca($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveBacheca(Bacheca $bacheca)
    {
        $data = array(
            'id' => $bacheca->id,
            'object'  => $bacheca->object,
            'message'  => $bacheca->message,
            'condominium_id'  => $bacheca->condominium_id,
            'condomino_id'  => $bacheca->condomino_id,
            'creationdate' => date_create($bacheca->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($bacheca->modifydate)->format('Y-m-d H:i:s')
        );

        $id = (int)$bacheca->id;

        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getBacheca($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteBacheca($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

}