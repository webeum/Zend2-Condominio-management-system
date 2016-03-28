<?php
namespace Request\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Common\Utils;

class RequestTable
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

    public function getRequests($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'object', 'message', 'note', 'status_id', 'type_id', 'condominium_id', 'condomino_id', 'creationdate', 'modifydate'));
        $select->join('condomino', 'condomino.id = request.condomino_id', array('firstname', 'lastname'), 'inner');
        $select->where(array('condominium_id' => $condominium_id));
        $select->order('creationdate DESC');
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getRequest($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
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

    public function saveRequest(Request $request)
    {
        $data = array(
            'id' => $request->id,
            'object'  => $request->object,
            'message'  => $request->message,
            'note'  => $request->note,
            'status_id'  => $request->status_id,
            'type_id'  => $request->type_id,
            'condominium_id'  => $request->condominium_id,
            'condomino_id'  => $request->condomino_id,
            'creationdate' => date_create($request->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($request->modifydate)->format('Y-m-d H:i:s')
        );

        $id = (int)$request->id;

        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getRequest($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteRequest($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

}