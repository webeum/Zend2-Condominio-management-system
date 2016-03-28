<?php
namespace Category\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Expression;
use Common\Utils;

class CategoryTable
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

    public function getCategory($id)
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

    public function saveCategory(Category $category)
    {
        $data = array(
            'name' => $category->name,
            'ranking'  => $category->ranking,
            'creationdate' => date_create($category->creationdate)->format('Y-m-d H:i:s'),
            'modifydate'  => date_create($category->modifydate)->format('Y-m-d H:i:s')
        );

        $id = (int)$category->id;
        if ($id == 0) 
        {
            $this->tableGateway->insert($data);
        } 
        else 
        {
            if ($this->getCategory($id)) 
            {
                $this->tableGateway->update($data, array('id' => $id));
            } 
            else 
            {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCategory($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function getCategoryOrderByRanking()
    {

        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('id','name','ranking','creationdate','modifydate'));
        $select->join('document', 'document.category_id = category.id', array('docs' => new Expression('COUNT(document.id)')), 'left');
        $select->group('category.name');
        $select->order('ranking ASC');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;


        //$select35->from('foo')->columns(array())->join('bar', 'm = n', array('thecount' => new Expression("COUNT(*)")));
    }

}