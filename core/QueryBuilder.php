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
    private $sqlPaginate = '';
    private $resetQuery = true;
    //
    function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }
    //
    function where($field, $compare = null, $value = null)
    {
        if ($field instanceof \Closure) {
            $callback = $field;
            $this->where .= '(';
            call_user_func_array($callback, [$this]);
            $this->where .= ')';
        } else {
            if (empty($this->where)) {
                $this->operator = ' WHERE ';
            } else {
                $this->operator = ' AND ';
            }
            $this->where .= "$this->operator $field $compare '$value'";
            $this->where = str_replace('( AND', ' AND (', $this->where);
        }
        return $this;
    }
    function whereIn($field, $value = [])
    {
        if (empty($this->where)) {
            $this->operator = ' WHERE ';
        } else {
            $this->operator = ' AND ';
        }
        $value = implode(',',$value);
        $this->where .= "$this->operator $field IN ($value)";
        $this->where = str_replace('( AND', ' AND (', $this->where);

        return $this;
    }
    //
    public function orWhere($field, $compare, $value)
    {
        if ($field instanceof \Closure) {
            $callback = $field;
            $this->where .= '(';
            call_user_func_array($callback, [$this]);
            $this->where .= ')';
        } else {
            if (empty($this->where)) {
                $this->operator = ' WHERE ';
            } else {
                $this->operator = ' OR ';
            }

            $this->where .= "$this->operator $field $compare '$value'";
            $this->where = str_replace('( OR', ' OR (', $this->where);
        }

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
    function orderBy($field, $type = 'ASC')
    {
        $fieldArr = array_filter(explode(',', $field));
        if (!empty($fieldArr) && count($fieldArr) >= 2) {
            // SQL order by multi
            $this->orderBy = "ORDER BY " . implode(',  ', $fieldArr);
        } else {
            $this->orderBy = "ORDER BY $field $type";
        }
        return $this;
    }

    function paginate($limit, $isQuery = true)
    {
        $request = new Request();
        $field = $request->getFields();
        $page = !empty($field['page']) && $field['page'] > 0 ? $field['page'] : 1;
        $offset = ($page - 1) * $limit;
        $this->resetQuery = false;
        $result = $this->limit($limit, $offset)->get();
        $query = $this->query($this->sqlPaginate);
        $totalRows = $query->rowCount();
        $totalPage = ceil($totalRows / $limit);

        if ($page > $totalPage) {
            $page = 1;
            $result = $this->limit($limit, 0)->get();
        }

        $paginateView = Paginate::render($query, $limit, $page, $totalPage, $isQuery);
        $this->resetQuery();
        $this->resetQuery = true;

        return [
            'data' => $result,
            'link' => $paginateView,
        ];
    }
    //
    private function getPaginateLink($page, $isQuery)
    {
        if (!empty($_SERVER['QUERY_STRING']) && $isQuery) {
            $queryString = trim($_SERVER['QUERY_STRING']);

            parse_str($queryString, $params);
            $params['page'] = $page;
        } else {
            $params = ['page' => $page];
        }
        $link = http_build_query(($params));
        $link = strpos($link, '?') !== false ? $link : '?' . $link;
        return $link;
    }
    //
    private function getPaginateView($limit, $page, $isQuery)
    {
        $query = $this->query($this->sqlPaginate);
        $totalRows = $query->rowCount();
        $totalPage = ceil($totalRows / $limit);

        $pageHtml = '';
        for ($i = 1; $i <= $totalPage; $i++) {
            $pageHtml .= '<li class="page-item' . ($page == $i ? ' active' : null) . '"><a class="page-link" href="' . $this->getPaginateLink($i, $isQuery) . '">' . $i . '</a></li>';
        }

        $html = '<nav class="d-flex justify-content-end ">
                        <ul class="pagination">
                            ' . ($page > 1 ? '<li class="page-item"><a class="page-link" href="' . $this->getPaginateLink($page - 1, $isQuery) . '">Trước</a></li>' : '<li class="page-item disabled"><a class="page-link" href="">Trước</a></li>')
            . $pageHtml
            . ($page < $totalPage ? '<li class="page-item"><a class="page-link" href="' . $this->getPaginateLink($page + 1, $isQuery) . '">Sau</a></li>' : '<li class="page-item disabled"><a class="page-link" href="">Sau</a></li>')
            . '</ul>
        </nav>';
        return $html;
    }
    // Inner join
    function join($tableName, $relationShip)
    {
        $this->innerJoin .= " INNER JOIN $tableName ON $relationShip ";
        return $this;
    }
    //
    function get()
    {
        $sqlQuery = "SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where $this->orderBy $this->limit";
        $sqlQuery = trim($sqlQuery);

        $this->sqlPaginate = trim("SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where");

        $query = $this->query($sqlQuery);

        // Reset field
        if ($this->resetQuery) {
            $this->resetQuery();
        }

        if (!empty($query)) {
            return $query->fetchAll(pdo::FETCH_ASSOC);
        }
        return false;
    }
    // Insert
    function insert($data)
    {
        $tableName = $this->tableName;
        $insertStatus = $this->insertData($tableName, $data);
        return $insertStatus;
    }
    // Last id
    function lastId()
    {
        return $this->lastInsertId();
    }
    // Update
    function update($data)
    {
        $whereUpdate = str_replace('WHERE', '', $this->where);
        $whereUpdate = trim($whereUpdate);
        $tableName = $this->tableName;
        $statusUpdate = $this->updateData($tableName, $data, $whereUpdate);
        return $statusUpdate;
    }
    // Delete
    function delete()
    {
        $whereDelete = str_replace('WHERE', '', $this->where);
        $whereDelete = trim($whereDelete);
        //echo $whereDelete;
        $tableName = $this->tableName;

        $statusDelete = $this->deleteData($tableName, $whereDelete);
        return $statusDelete;
    }
    //
    function first()
    {
        $sqlQuery = "SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where $this->orderBy $this->limit";
        $sqlQuery = trim($sqlQuery);

        $this->sqlPaginate = trim("SELECT $this->selectField FROM $this->tableName $this->innerJoin $this->where");

        $query = $this->query($sqlQuery);

        // Reset field
        if ($this->resetQuery) {
            $this->resetQuery();
        }

        if (!empty($query)) {
            return $query->fetch(pdo::FETCH_ASSOC);
        }
        return false;
    }
    //
    function resetQuery()
    {
        // Reset field
        $this->tableName = '';
        $this->where = '';
        $this->operator = '';
        $this->selectField = '*';
        $this->limit = '';
        $this->orderBy = '';
        $this->innerJoin = '';
        $this->sqlPaginate = '';
    }
}
