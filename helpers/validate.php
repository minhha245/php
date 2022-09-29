<?php
function validateEmail($email)
{
    $data = array();
    $pattern = "/^[a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9]+\.[a-zA-Z0-9]{2,6}$/";

    if (!empty($email)) {
        if (!preg_match($pattern, $email, $matches)) {
            $data['error-email'] = ERROR_VALID_EMAIL;
        }

    }

    return $data;
}

function validatePassword($password)
{
    $data = array();
    $pattern = "/^([\w_\.!@#$%^&*()-]+)$/";
    // $pattern = "/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z0-9!@#$%&*]{6,100}$/";
    if (!empty($password)) {
        if (!preg_match($pattern, $password, $matches)) {
            $data['error-password'] = ERROR_VALID_PASSWORD;
        }

    }

    return $data;
}

function validateName($name)
{
    $data = array();
    $pattern = "/^([a-zA-Z0-9ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+)$/";

    if (!empty($name)) {
        if (!preg_match($pattern, $name, $matches)) {
            $data['error-name'] = ERROR_VALID_NAME;
        }

    }

    return $data;
}

function validateImg()
{
    $data = array();

    if ($_FILES['avatar']['name'] != "") {
        $type_allow = ['jpg', 'jpeg', 'png', 'gif'];
        $type_img = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($type_img), $type_allow)) {
            $data["error-avatar"] = IMAGE_INVALID;
        } else {

            $size_img = $_FILES['avatar']['size'];
            if ($size_img > 29000000) {
                $data["error-avatar"] = IMAGE_MAX_SIZE;
            }

        }
    }

    return $data;
}

function checkLengthEmail($email)
{
    $data = array();

    if (!empty($email)) {
        if (strlen($email) < 3 || strlen($email) > 255) {
            $data['error-email'] = ERROR_LENGTH_EMAIL;
        }

    }

    return $data;
}

function checkLengthName($name)
{
    $data = array();

    if (!empty($name)) {
        if (strlen($name) < 3 || strlen($name) > 255) {
            $data['error-name'] = ERROR_LENGTH_NAME;
        }

    }

    return $data;
}

function checkLengthPassword($password)
{
    $data = array();

    if (!empty($password)) {
        if (strlen($password) < 6 || strlen($password) > 8) {
            $data['error-password'] = ERROR_LENGTH_PASSWORD;
        }

    }

    return $data;
}
function checkConfirmPassword($password, $confirm_password)
{
    $data = array();

    if (isset($password)) {
        if ($password != $confirm_password) {
            $data["error-confirm-password"] = ERROR_CONFIRM_PASSWORD;
        }

    }

    return $data;
}
