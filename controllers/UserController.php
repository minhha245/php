<?php
require_once "BaseController.php";
require_once "models/UserModel.php";
require_once 'vendor/facebook/graph-sdk/src/Facebook/autoload.php';

class UserController extends BaseController
{
    public function __construct()
    {
        $this->folder = "user";
        $this->UserModel = new UserModel();
    }

    public function error()
    {
        $this->render('error');
    }

    public function edit()
    {
        $id = $_GET['id'];
        $data = $this->UserModel->getInfoUserByID($id);
        $error = array();

        if (isset($_POST['save'])) {
            $avatar = $_FILES['avatar']['name'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm-password'];
            $status = $_POST['status'];
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
                if ($this->UserModel->checkExistsEmailUser($email) > 0) {
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
                $upd_id_user = $this->UserModel->getInfoAdminByEmail($_SESSION['admin']['login']['email']);
                $arr = array(
                    'avatar' => $avatar,
                    'name' => $name,
                    'email' => $email,
                    'password' => md5($password),
                    'status' => $status,
                    'upd_id' => $upd_id_user['id'],
                    'upd_datetime' => date("Y-m-d H:i:s a"),
                );

                $upload_file = UPLOADS_USER . $_FILES['avatar']['name'];

                if ($this->UserModel->update($arr, "`id` = '{$id}'")) {
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file);
                    $_SESSION['alert']['update-success'] = UPDATE_SUCCESSFUL . " with ID = {$id}";
                    header("Location: index.php?controller=user&action=search");
                } else {
                    $_SESSION['alert']['update-fail'] = UPDATE_ERROR;
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
        if ($this->UserModel->delete("`id`={$id}")) {
            $_SESSION['alert']['delete-success'] = DELETE_SUCCESSFUL . " with ID = {$id}";
        } else {
            $_SESSION['alert']['delete-fail'] = DELETE_ERROR . " with ID = {$id}";
        }

        header("Location: index.php?controller=user&action=search");
    }

    // Users
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

                if ($this->UserModel->checkLogin($data)) {
                    $_SESSION['user']['login'] = [
                        'is_login' => 1,
                        'email' => $_POST['email'],
                        'password' => $_POST['password'],
                    ];
                    header("Location: index.php?controller=user&action=detail");
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

    public function loginViaFB()
    {
        $fb = new Facebook\Facebook([
            'app_id' => APP_ID,
            'app_secret' => APP_SECRET,
            'default_graph_version' => DEFAULT_GRAPH_VERSION,
        ]);
        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            // echo 'Facebook SDK returned an error: ' . $e->getMessage();
            header("Location: index.php?controller=user&action=login");

            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }
        // Logged in
        $me = $response->getGraphUser();
        $_SESSION['fb_access_token'] = (string) $accessToken;
        $data = array();
        if ($this->UserModel->checkExistsEmailUser($me->getEmail()) > 0) {
            $getInfoUserByEmail = $this->UserModel->getInfoUserByEmail($me->getEmail());
            $data = [
                'facebook_id' => $me->getId(),
                'avatar' => $getInfoUserByEmail['avatar'],
                'name' => $me->getName(),
                'email' => $me->getEmail(),
            ];
        } else {
            $url = "https://graph.facebook.com/{$me->getId()}/picture";
            $data = file_get_contents($url);
            $fileName = "fb-profilepic-{$me->getId()}.jpg";
            $file = fopen(UPLOADS_USER . $fileName, 'w+');
            fputs($file, $data);
            fclose($file);
            $data = array(
                'id' => "",
                'avatar' => $fileName,
                'facebook_id' => $me->getId(),
                'name' => $me->getName(),
                'email' => $me->getEmail(),
                'ins_datetime' => date("Y-m-d H:i:s a"),
            );
            $this->UserModel->insert($data);
        }

        $_SESSION['user']['loginFB-success'] = LOGIN_FB_SUCCESSFUL;

        $this->render("detail", $data);
    }

    public function logout()
    {
        unset($_SESSION['user']['login']);
        unset($_SESSION['fb_access_token']);
        header("Location: index.php?controller=user&action=login");
    }

    public function detail()
    {
        $data = $this->UserModel->getInfoUserByEmail($_SESSION['user']['login']['email']);
        $this->render('detail', $data);
    }

    public function search()
    {
        if (isset($_GET['reset'])) {
            header("Location: index.php?controller=user&action=search");
        }

        $email = isset($_GET['email']) ? $_GET['email'] : "";
        $name = isset($_GET['name']) ? $_GET['name'] : "";
        $search = isset($_GET['search']) ? $_GET['search'] : "";
        $add_url_search = "&email={$email}&name={$name}&search={$search}";
        $record_per_page = RECORD_PER_PAGE;
        /**
         * Sort
         */
        $sort = "DESC";
        $getSort = "";
        if (isset($_GET['sort'])) {
            $getSort = $_GET['sort'];
            if ($_GET['sort'] == $sort) {
                $sort = "ASC";
            }

        }
        $column = isset($_GET['column']) ? $_GET['column'] : "id";
        $add_url_pagging = $search . "&column=" . $column . "&sort=" . $getSort;
        $value = [
            'email' => $email,
            'name' => $name,
        ];
        $total_record = $this->UserModel->getSearchAll($value);
        $array = $this->pagging($total_record);
        $value = array_merge($value,
            [
                'column' => $column,
                'getSort' => $getSort,
                'start' => $array['start'],
                'record_per_page' => RECORD_PER_PAGE,
            ]);
        $data = $this->UserModel->getInfoSearch($value);

        if (empty($data)) {
            $data = NO_EXISTS_USER;
        }

        $arr = array_merge($array, [
            'data' => $data,
            'sort' => $sort,
            'add_url_search' => $add_url_search,
            'add_url_pagging' => $add_url_pagging,
        ]);

        $this->render('search', $arr);
    }
}
