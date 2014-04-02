<?php
namespace MatMuh;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\RowGateway\RowGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Slim\Slim;
use Exception;

class Table
{
    protected $_name;
    protected $_primary;
    protected $_tableGateway;
    protected $_sequence;
    protected $_db;
    public $_log = true;
    public $_error;
    protected $_cols;

    public function __construct()
    {
        $config = include ROOT_DIR . '/application/config/config.php';
        $this->_db = new \Zend\Db\Adapter\Adapter($config['db']);
        $this->_tableGateway = new TableGateway($this->_name, $this->_db);
        //$this->customQuery("SET SESSION time_zone ='+2:00'", false);
    }

    public function selectRow($id)
    {
        $where = new Where();

        if (is_array($this->_primary)) {
            foreach ($this->_primary as $key) {
                if (isset($id[$key]))
                    $where->equalTo($key, $id[$key]);
            }
        }
        else {
            if (isset($id))
                $where->equalTo($this->_primary, $id);
        }

        if (count($where->getPredicates()) == 0)
            return null;

        $select = $this->_tableGateway->getSql()->select()->where($where)->limit(1);
        $rowset = $this->_tableGateway->getSql()->prepareStatementForSqlObject($select)->execute();

        return $rowset->current();
    }

    public function select($param, $returnSql = false)
    {
        if ($param['api_test']) {
            unset($param['api_test']);
            $param = $this->filtre($param);
        }

        if (isset($param['where']))
            $param['where'] = $this->getWhere($param['where']);

        if (isset($param['having']))
            $param['having'] = $this->getWhere($param['having']);

        $select = $this->_tableGateway->getSql()->select();

        if (isset($param['where']))
            $select->where($param['where']);

        if (isset($param['offset']))
            $select->offset((int)$param['offset'])->limit(1);

        if (isset($param['limit']))
            $select->limit((int)$param['limit']);

        if (isset($param['order']))
            $select->order($param['order']);

        if (isset($param['cols']))
            $select->columns($param['cols']);

        if (isset($param['group']))
            $select->group($param['group']);

        if (isset($param['having']))
            $select->columns($param['having']);

        $sql = $select->getSqlString();

        if ($param['cursor'])
            $rows = $this->_tableGateway->selectWith($select);
        else {
            $result = $this->_tableGateway->getSql()->prepareStatementForSqlObject($select)->execute();

            $rowset = new ResultSet();
            $rows = $rowset->initialize($result)->toArray();
        }

        if ($param['total_count']) {
            $select->reset('order')->reset('columns')->reset('limit')->reset('offset');
            $select->columns(array('count' => new Expression('count(*)')));
            $count = $this->_tableGateway->selectWith($select)->current()->count;

            $res = new \stdClass();
            $res->rows = $rows;
            $res->rowCount = $count;

            return $res;
        }
        else {
            if (isset($param['limit']) && $param['limit'] == 1)
                return $rows[0];
            else
                return $rows;

            if ($returnSql)
                $rows['sql'] = $sql;
        }
    }

    public function insert($data)
    {
        $this->_error = null;
        if ($data['api_test'])
            unset($data['api_test']);

        try {
            if ($this->_tableGateway->insert($data)) {
                if ($this->_sequence) {
                    $lastVal = $this->_tableGateway->lastInsertValue;

                if (is_array($this->_primary))
                    $data[$this->_primary[0]] = $lastVal;
                else
                    $data[$this->_primary] = $lastVal;
                }

                if (is_array($this->_primary)) {
                    $id = array();

                    foreach ($this->_primary as $key) {
                        $id[$key] = $data[$key];
                    }
                }
                else {
                    if ($this->_sequence)
                        $id = $lastVal;
                    else
                        $id = $data[$this->_primary];
                }

                return $id;
            }

            return false;
        }
        catch (Exception $e) {
            $this->_error = $e->getPrevious();
            return false;
        }
    }

    public function update($where, $data)
    {
        $this->_error = null;

        if (!is_array($where)) {
            if (!is_array($this->_primary))
                $where = array($this->_primary => $where);
            else
                return false;
        }

        $where = $this->getWhere($where);

        try {
            $r = $this->_tableGateway->update($data, $where);

            if ($r == 0)
                $r = true;

            return $r;
        }
        catch (Exception $e) {
            $this->_error = $e->getPrevious();
            return false;
        }
    }

    public function delete($where)
    {
        $this->_error = null;

        if (!is_array($where)) {
            if (!is_array($this->_primary))
                $where = array($this->_primary => $where);
            else
                return false;
        }

        $where = $this->getWhere($where);

        try {
            $r = $this->_tableGateway->delete($where);
            return $r;
        }
        catch (Exception $e) {
            $this->_error = $e->getPrevious();
            return false;
        }
    }

    public function selectPairs($key, $val, $param)
    {
        $rows = $this->select($param);
        $arr = array();

        foreach ($rows as $r) {
            $arr[$r[$key]] = $r[$val];
        }

        return $arr;
    }

    public function exist($where)
    {
        $param = array('where' => $where, 'limit' => 1);
        $rows = $this->select($param);

        if (count($rows) > 0)
            return true;
        else
            return false;
    }

    private function getWhere($criterion)
    {
        $where = new Where();

        foreach ($criterion as $key => $val) {
            $oKey = $this->trim($key);

            if (isset($val)) {
                if (is_array($val))
                    $where->in($oKey,$val);
                else {
                    if (strstr($key, '%'))
                        $where->like($oKey, '%' . $val . '%');
                    else if (strstr($key, '!'))
                        $where->notEqualTo($oKey, $val);
                    else if (strstr($key, '>='))
                        $where->greaterThanOrEqualTo($oKey, $val);
                    else if (strstr($key, '>'))
                        $where->greaterThan($oKey, $val);
                    else if (strstr($key, '<='))
                        $where->lessThanOrEqualTo($oKey, $val);
                    else if (strstr($key, '<'))
                        $where->lessThan($oKey, $val);
                    else if (strstr($key, 'null'))
                        $where->isNull($oKey);
                    else if (strstr($key, 'notnull'))
                        $where->isNotNull($oKey);
                    else if (is_numeric($key))
                        $where->literal($val);
                    else
                        $where->equalTo($oKey, $val);
                }
            }
        }

        return $where;
    }

    public function trim($key)
    {
        return str_replace(array('%', '!', '>', '<', '=', ' ', 'null', 'notnull'), '', $key);
    }

    public function filter($where)
    {
        if (!$where['where']) {
            if ($where['limit'])
                $filter['limit'] = $where['limit'];

            if ($where['offset'])
                $filter['offset'] = $where['offset'];

            if ($where['order'])
                $filter['order'] = $where['order'];

            if ($where['cols'])
                $filter['cols'] = $where['cols'];

            if ($where['total_count'])
                $filter['total_count'] = $where['total_count'];

            unset($where['total_count']);
            unset($where["limit"]);
            unset($where["offset"]);
            unset($where["order"]);
            unset($where["cols"]);

            foreach ($where as $key => $value) {
                if (is_array($value))
                    $filter["where"][$key] = $value;
                else
                    $filter["where"][$key . $operator] = $value;
            }

            return $filter;
        }
        else
            return $where;
    }

    function customQuery($sql, $toArray=true)
    {
        $statement = $this->_db->query($sql);
        $data = $statement->execute();

        if ($toArray) {
            foreach ($data as $res) {
                $results[] = $res;
            }

            return $results;
        }
        else
            return $data;
    }

    public function filterData($data, $remove_empty = true)
    {
        if ($this->_cols) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->_cols))
                    unset($data[$key]);
                else if ($remove_empty && !isset($value))
                    unset($data[$key]);
            }
        }

        return $data;
    }
}