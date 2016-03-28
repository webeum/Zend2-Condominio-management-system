<?php
namespace Document\Model;

use Zend\Db\TableGateway\TableGateway;
use Common\Utils;

class DocumentTable
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

    public function getDocument($id)
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

    public function checkPrivileges($id, $condominium_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('id' => $id), array('condominium_id' => $condominium_id));
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

    public function saveDocument(Document $document)
    {
        $data = array(
            'name' => $document->name,
            'description' => $document->description,
            'filename' => $document->filename,
            'mime' => $document->mime,
            'size' => $document->size,
            'data' => $document->data,
            'category_id'  => $document->category_id,
            'condominium_id'  => $document->condominium_id,
            'creationdate' => date_create($document->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($document->modifydate)->format('Y-m-d H:i:s')
        );

        $id = (int)$document->id;
        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getDocument($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteDocument($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

        public function deleteDocumentByCategory($category_id)
    {
        $this->tableGateway->delete(array('category_id' => $category_id));
    }

    public function getDocumentByCategory($condominium_id, $category_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where(array('category_id' => $category_id));
        $select->where(array('condominium_id' => $condominium_id));
        $select->order('creationdate DESC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

}