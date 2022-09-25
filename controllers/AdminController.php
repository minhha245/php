<?php
require_once "BaseController.php";
require_once "models/AdminModel.php";
class AdminController extends BaseController
{
    public function __construct()
    {
        $this->folder = "admin";
        $this->AdminModel = new AdminModel();

        if (isset($_SESSION['admin']['role_type']) && $_SESSION['admin']['role_type'] != 1) {

            header("Location: index.php?controller=user&action=search");

        }
    }

    public function error()
    {
        $this->render('error');
    }

    public function login()
    {
        $error = array();
        if (isset($_POST['login'])) {

            if (empty($_POST['email'])) {
                $error['error-empty-email'] = ERROR_EMPTY_EMAIL;
            }

            if (empty($_POST['password'])) {
                $error['error-empty-password'] = ERROR_EMPTY_PASSWORD;
            }

            $email = $_POST['email'];
            $password = md5($_POST['password']);
            $data = [
                'email' => $email,
                'password' => $password,
            ];

            if (empty($error)) {
                $admin = $this->AdminModel->checkLogin($data);

                if ($admin != null) {
                    $_SESSION['admin']['login'] = [
                        'is_login' => 1,
                        'email' => $email,
                    ];
                    $get_role = $this->AdminModel->getRoleAdmin($_SESSION['admin']['login']['email']);
                    $_SESSION['admin']['role_type'] = $get_role['role_type'];

                    if ($_SESSION['admin']['role_type'] == 1) {
                        header("Location: index.php?controller=admin&action=search");
                    } else {
                        header("Location: index.php?controller=user&action=search");
                    }

                } else {
                    $error['error-login'] = ERROR_LOGIN;
                    $this->render('login', $error);
                }
            } else {
                $this->render('login', $error);
            }

        } else {
            $this->render('login', $error);
        }
    }

    public function logout()
    {
        unset($_SESSION['admin']);
        header("Location: index.php?controller=admin&action=login");
    }

    public function search()
    {
        if (isset($_GET['reset'])) {
            header("Location: index.php?controller=admin&action=search");
        }

        $email = isset($_GET['email']) ? $_GET['email'] : "";
        $name = isset($_GET['name']) ? $_GET['name'] : "";
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $add_url_search = "&email={$email}&name={$name}";
        $sort = "DESC";
        $getSort = "";

        if (isset($_GET['sort'])) {
            $getSort = $_GET['sort'];
            if ($_GET['sort'] == $sort) {
                $sort = "ASC";
            }
        }

        $column = isset($_GET['column']) ? $_GET['column'] : "id";
        $add_url_pagging = $add_url_search . "&column=" . $column . "&sort=" . $getSort;
        $value = [
            'email' => $email,
            'name' => $name,
        ];
        $total_record = $this->AdminModel->getSearchAll($value);
        $array = $this->pagging($total_record);
        $value = array_merge($value,
            [
                'column' => $column,
                'getSort' => $getSort,
                'start' => $array['start'],
                'record_per_page' => RECORD_PER_PAGE,
            ]);

        $data = $this->AdminModel->getInfoSearch($value);

        if (empty($data)) {
            $data = NO_EXISTS_USER;
        }

        $arr = [
            'data' => $data,
            'sort' => $sort,
            'add_url_search' => $add_url_search,
            'add_url_pagging' => $add_url_pagging,
        ];
        $arrays = array_merge($arr, $array);
        $this->render('search', $arrays);
    }

    public function create()
    {
        $data = array();

        if (isset($_POST['reset'])) {
            header("Location: index.php?controller=admin&action=create");
        }

        if (isset($_POST['save'])) {

            if ($_FILES['avatar']['name'] == "") {
                $data['error-avatar'] = ERROR_EMPTY_AVATAR;
            }

            if (empty($_POST['email'])) {
                $data['error-email'] = ERROR_EMPTY_EMAIL;
            }

            if (empty($_POST['name'])) {
                $data['error-name'] = ERROR_EMPTY_NAME;
            }

            if (empty($_POST['password'])) {
                $data['error-password'] = ERROR_EMPTY_PASSWORD;
            }

            if (empty($_POST['confirm-password'])) {
                $data['error-confirm-password'] = ERROR_EMPTY_CONFIRM_PASSWORD;
            }

            $checkLengthEmail = checkLengthEmail($_POST['email']);
            $checkLengthName = checkLengthName($_POST['name']);
            $checkLengthPassword = checkLengthPassword($_POST['password']);
            $validEmail = validateEmail($_POST['email']);
            $validName = validateName($_POST['name']);
            $validPassword = validatePassword($_POST['password']);
            $validImg = validateImg();

            $data = array_merge($data, $checkLengthEmail, $checkLengthName, $checkLengthPassword, $validEmail, $validName, $validPassword, $validImg);

            if (($this->AdminModel->checkExistsEmailAdmin($_POST['email'])) > 0) {
                $data['error-email'] = ERROR_EMAIL_EXISTS;
            }

            if ($_POST['password'] != $_POST['confirm-password']) {
                $data['error-confirm-password'] = ERROR_CONFIRM_PASSWORD;
            }

            $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];

            $ins_id_admin = $this->AdminModel->getIdAdmin($_SESSION['admin']['login']['email']);

            if (empty($data)) {
                $arr = array(
                    'avatar' => $_FILES['avatar']['name'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'role_type' => $_POST['role_type'],
                    'ins_id' => $ins_id_admin['id'],
                    'ins_datetime' => date("Y-m-d H:i:s a"),
                );
                if ($this->AdminModel->insert($arr)) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['admin']['upload'] = $upload_file;
                    $_SESSION['alert']['create-success'] = INSERT_SUCCESSFUL;
                    header("Location: index.php?controller=admin&action=search");
                } else {
                    $_SESSION['alert']['create-fail'] = INSERT_ERROR;
                    header("Location: index.php?controller=admin&action=search");
                }
            }
        }
        $this->render('create', $data);
    }

    public function edit()
    {
        $id = $_GET['id'];
        $data = $this->AdminModel->getInfoAdmin($id);
        $error = array();

        if (isset($_POST['save'])) {
            $avatar = $_FILES['avatar']['name'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm-password'];
            $role_type = $_POST['role_type'];

            $validImg = validateImg($avatar);
            $validName = validateName($name);
            $validEmail = validateEmail($email);
            $validPass = validatePassword($password);
            $checkConfirmPass = checkConfirmPassword($password, $confirm_password);

            if (empty($_POST['email'])) {
                $error['error-email'] = ERROR_EMPTY_EMAIL;
            }

            if (empty($_POST['name'])) {
                $error['error-name'] = ERROR_EMPTY_NAME;
            }

            if (!empty($avatar)) {
                $error = array_merge($error, $validImg);
            } else {
                $avatar = $data['avatar'];
            }

            if ($name != $data['name']) {
                $error = array_merge($error, $validName);
            }

            if ($email != $data['email']) {
                if ($this->AdminModel->checkExistsEmailAdmin($email) > 0) {
                    $error['error-email'] = ERROR_EMAIL_EXISTS;
                }

                $error = array_merge($error, $validEmail);
            }

            if (!empty($password)) {
                $error = array_merge($error, $validPass, $checkConfirmPass);
            } else {
                $password = $data['password'];
            }

            $checkLengthEmail = checkLengthEmail($_POST['email']);
            $checkLengthName = checkLengthName($_POST['name']);
            $checkLengthPassword = checkLengthPassword($_POST['password']);

            $error = array_merge($error, $checkLengthEmail, $checkLengthName, $checkLengthPassword);

            if (empty($error)) {
                $upd_id = $this->AdminModel->getIdAdmin($_SESSION['admin']['login']['email']);
                $arr = array(
                    'avatar' => $avatar,
                    'name' => $name,
                    'email' => $email,
                    'password' => md5($password),
                    'role_type' => $role_type,
                    'upd_id' => $upd_id['id'],
                    'upd_datetime' => date("Y-m-d H:i:s a"),
                );

                $upload_file = UPLOADS_ADMIN . $_FILES['avatar']['name'];

                if ($this->AdminModel->update($arr, "`id` = '{$id}'")) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['alert']['update-success'] = UPDATE_SUCCESSFUL . " with ID = {$id}";
                    header("Location: index.php?controller=admin&action=search");
                } else {
                    $_SESSION['alert']['update-fail'] = UPDATE_ERROR . " with ID = {$id}";
                    header("Location: index.php?controller=admin&action=search");
                }
            }
        }

        $temp = array(
            'error' => $error,
            'data' => $data,
        );

        $this->render('edit', $temp);
    }

    public function delete()
    {
        $id = $_GET['id'];

        if ($this->AdminModel->delete("`id`={$id}")) {
            $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL . " with ID = {$id}";
        } else {
            $_SESSION['alert']['delete-fail'] = DELETE_ERROR;
        }

        header("Location: index.php?controller=admin&action=search");
    }
}
