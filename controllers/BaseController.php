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
    public function uploadImg($target_dir)
    {
        $error = [];
        $flag = true;
        $target_file = $target_dir . basename($_FILES['avatar']['name']);
        $imgFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (($_FILES['avatar']['name']) == '') {
            $error['file_blank'] = 'file blank';
            $flag = false;
        }

        $allowType = ['jpg', 'png', 'jpeg', 'gif'];

        if (!in_array($imgFileType, $allowType)) {
            $error['file_format'] = "File is not in the correct format ";
            $flag = false;
        }

        if ($flag) {
            return $target_file;
        }

        return $error;
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
