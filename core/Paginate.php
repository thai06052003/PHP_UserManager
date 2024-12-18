<?php
class Paginate
{
    // lấy link phân trang
    private static function getPaginateLink($page, $isQuery)
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
    private static function getView($data=[]) {
        extract($data);
        ob_start();
        require './core/views/paginate.php';
        $view = ob_get_contents();
        ob_end_clean();
        return $view;
    }

    public static function render($query, $limit, $page, $totalPage, $isQuery)
    {
        $totalRows = $query->rowCount();
        $totalPage = ceil($totalRows / $limit);

        $self = __CLASS__;

        $begin = max(2, $page-2);
        $end = min($page+2, $totalPage-1);
        $view = self::getView(compact('page', 'totalPage', 'self', 'isQuery', 'begin', 'end'));
        if ($totalPage >1 ) {
            return $view;
        }
    }
}
