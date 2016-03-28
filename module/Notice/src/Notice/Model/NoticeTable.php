<?php
namespace Notice\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;
use Common\Utils;

class NoticeTable
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

    public function getNoticeMessages($condominium_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id', 'object', 'message', 'condominium_id', 'administrator_id', 'creationdate', 'modifydate'));
        $select->join('administrator', 'administrator.id = notice.administrator_id', array('firstname', 'lastname'), 'inner');
        $select->where(array('condominium_id' => $condominium_id));
        $select->order('creationdate DESC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    public function checkPrivileges($id, $administrator_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('id' => $id), array('administrator_id' => $administrator_id));
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

    public function getNotice($id)
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

    public function saveNotice(Notice $notice)
    {
        $data = array(
            'id' => $notice->id,
            'object'  => $notice->object,
            'message'  => $notice->message,
            'condominium_id'  => $notice->condominium_id,
            'administrator_id'  => $notice->administrator_id,
            'creationdate' => date_create($notice->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($notice->modifydate)->format('Y-m-d H:i:s')
        );

        $id = (int)$notice->id;

        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getNotice($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteNotice($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

}