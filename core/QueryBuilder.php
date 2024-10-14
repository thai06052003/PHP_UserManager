<?php
trait QueryBuilder
{
    public $tableName = '';
    public $where = '';
    public $operator = '';
    public $selectField = '*';
    public $limit = '';
    public $orderBy = '';
    public $innerJoin = '';
    //
    function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }
    //
    function where($field, $compare, $value)
    {
        if (empty($this->where)) {
            $this->operator = ' WHERE ';
        } else {
            $this->operator = ' AND ';
        }

        $this->where .= "$this->operator $field $compare '$value'";
        return $this;
    }
    //
    public function orWhere($field, $compare, $value)
    {
        if (empty($this->where)) {
            $this->operator = ' WHERE ';
        } else {
            $this->operator = ' OR ';
        }

        $this->where .= "$this->operator $field $compare '$value'";
        return $this;
    }
    //
    public function whereLike($field, $value)
    {
        if (empty($this->where)) {
            $this->operator = ' WHERE ';
        } else {
            $this->operator = ' AND ';
        }

        $this->where .= "$this->operator $field LIKE '$value'";
        return $this;
    }
    //
    function select($field = '*')
    {
        $this->selectField = $field;
        return $this;
    }
    //
    function limit($number, $offset = 0)
    {
        $this->limit = "LIMIT $offset, $number";
        return $this;
    }
    //
    function orderBy($field, $type='ASC') {
        $fieldArr = array_filter(explode(',', $field));
        if (!empty($fieldArr) && count($fieldArr)>=2) {
            // SQL order by multi
            $this->orderBy = "ORDER BY ".implode(',  ', $fieldArr);
        }
        else {
            $this->orderBy = "ORDER BY $field $type";
        }
        echo $this->orderBy;
        return $this;
    }
    // Inner join
    function join ($tableName, $relationShip) {
        $this->innerJoin .= " INNER JOIN $tableName ON $relationShip ";
        return $this;
    }
    //
    function get()
    {
        $sqlQuery = "SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where $this->orderBy $this->limit";
        $sqlQuery = trim($sqlQuery);
        $query = $this->query($sqlQuery);
        //echo $sqlQuery;

        // Reset field
        $this->reserQuery();

        if (!empty($query)) {
            return $query->fetchAll(pdo::FETCH_ASSOC);
        }
        return false;
    }
    // Insert
    function insert($data) {
        $tableName = $this->tableName;
        $insertStatus = $this->insertData($tableName, $data);
        return $insertStatus;
    }
    // Last id
    function lastId() {
        return $this->lastInsertId();
    }
    // Update
    function update($data) {
        $whereUpdate = str_replace('WHERE', '', $this->where);
        $whereUpdate = trim($whereUpdate);
        $tableName = $this->tableName;
        $statusUpdate = $this->updateData($tableName, $data, $whereUpdate);
        return $statusUpdate;
    }
    // Delete
    function delete() {
        $whereDelete = str_replace('WHERE', '', $this->where);
        $whereDelete = trim($whereDelete);
        $tableName = $this->tableName;

        $statusDelete = $this->deleteData($tableName, $whereDelete);
        return $statusDelete;
    }
    //
    function first()
    {
        $sqlQuery = "SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where $this->orderBy $this->limit";
        $sqlQuery = trim($sqlQuery);
        $query = $this->query($sqlQuery);

        // Reset field
        $this->reserQuery();

        if (!empty($query)) {
            return $query->fetch(pdo::FETCH_ASSOC);
        }
        return false;
    }
    //
    function reserQuery()
    {
        // Reset field
        $this->tableName = '';
        $this->where = '';
        $this->operator = '';
        $this->selectField = '*';
        $this->limit = '';
        $this->orderBy = '';
        $this->innerJoin = '';
    }
}
