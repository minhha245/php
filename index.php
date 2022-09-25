<?php
    require_once "connection.php";
    require_once "helpers/const.php";
    require_once "helpers/messages.php";
    require_once "helpers/fbconfig.php";
    require_once "helpers/validate.php";
date_default_timezone_set('Asia/Ho_Chi_Minh');

    session_start();
    ob_start();

    if (isset($_GET['controller'])) {
        $controller = $_GET['controller'];
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
        } else {
            $action = 'login';
        }
    } else {
        $controller = 'admin';
        $action = 'login';
        header("Location: index.php?controller=admin&action=login");
    }

    require_once('routes.php');