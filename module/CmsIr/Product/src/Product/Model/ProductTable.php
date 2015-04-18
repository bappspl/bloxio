<?php
namespace CmsIr\Product\Model;

use CmsIr\System\Model\ModelTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate;

class ProductTable extends ModelTable
{

    protected $tableGateway;

    protected $originalResultSetPrototype;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getProduct($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $result = $this->getResultSetAsArrayObject($rowset);
        if (!$result) {
            throw new \Exception("Could not find row $id");
        }
        return $result;
    }

    public function deleteProduct($id)
    {
        $id  = (int) $id;
        $this->tableGateway->delete(array('id' => $id));
    }

    public function getDataToDisplay ($filteredRows, $columns)
    {
        $dataArray = array();
        foreach($filteredRows as $row) {

            $tmp = array();

            foreach($columns as $column){
                $column = 'get'.ucfirst($column);
                $tmp[] = $row->$column();
            }

            $tmp[] = '<a href="products/edit/'.$row->getId().'" class="btn btn-primary" data-toggle="tooltip" title="Edycja"><i class="fa fa-pencil"></i></a> ' .
                     '<a href="products/delete/'.$row->getId().'" id="'.$row->getId().'" class="btn btn-danger" data-toggle="tooltip" title="Usuwanie"><i class="fa fa-trash-o"></i></a>';

            array_push($dataArray, $tmp);
        }
        return $dataArray;
    }

    public function getProductDatatables($columns, $data, $realizationId)
    {
        $displayFlag = false;

        $allRows = $this->getBy(array('realization_id' => $realizationId));
        $countAllRows = count($allRows);

        $trueOffset = (int) $data->iDisplayStart;
        $trueLimit = (int) $data->iDisplayLength;

        $sorting = array('id', 'asc');
        if(isset($data->iSortCol_0)) {
            $sorting = $this->getSortingColumnDir($columns, $data);
        }

        $where = array();
        if ($data->sSearch != '') {
            $where = array(
                new Predicate\PredicateSet(
                    $this->getFilterPredicate($columns, $data),
                    Predicate\PredicateSet::COMBINED_BY_OR
                ),
            );
            $where['realization_id'] = $realizationId;
            $displayFlag = true;
        } else {
            $where['realization_id'] = $realizationId;
        }

        $filteredRows = $this->tableGateway->select(function(Select $select) use ($trueLimit, $trueOffset, $sorting, $where){
            $select
                ->where($where)
                ->order($sorting[0] . ' ' . $sorting[1])
                ->limit($trueLimit)
                ->offset($trueOffset);
        });

        $dataArray = $this->getDataToDisplay($filteredRows, $columns);

        if($displayFlag == true) {
            $countFilteredRows = $filteredRows->count();
        } else {
            $countFilteredRows = $countAllRows;
        }

        return array('iTotalRecords' => $countAllRows, 'iTotalDisplayRecords' => $countFilteredRows, 'aaData' => $dataArray);
    }

    public function save(Product $product)
    {
        $data = array(
            'name' => $product->getName(),
            'slug' => $product->getSlug(),
            'date' => $product->getDate(),
            'url' => $product->getUrl(),
            'main_photo' => $product->getMainPhoto(),
            'realization_id' => $product->getRealizationId(),
            'category_id' => $product->getCategoryId(),
            'description' => $product->getDescription(),
        );

        $id = (int) $product->getId();
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;

        } else {
            if ($this->getOneBy(array('id' => $id))) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('ProductFile id does not exist');
            }
        }
        return $id;
    }
}