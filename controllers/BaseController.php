<?php
class BaseController
{
    protected $folder;

    public function render($file, $data = array())
    {
        $view_file = "views/" . $this->folder . "/" . $file . ".php";
        if (is_file($view_file)) {
            extract($data);
            require_once $view_file;
        } else {
            header("Location: index.php?controller=admin&action=error");
        }
    }

    public function pagging($total_record)
    {
        $record_per_page = RECORD_PER_PAGE;
        $total_page = ceil($total_record / $record_per_page);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page - 1) * $record_per_page;
        $previous = $page;
        $next = $page;

        if ($page > 1) {
            $previous = $page - 1;
        }

        if ($page < $total_page) {
            $next = $page + 1;
        }
        
        $arr = [
            'page' => $page,
            'total_page' => $total_page,
            'start' => $start,
            'previous' => $previous,
            'next' => $next,
            'record_per_page' => $record_per_page,
        ];

        return $arr;
    }
}
