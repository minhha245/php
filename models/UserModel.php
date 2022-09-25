<?php
require_once "models/BaseModel.php";

class UserModel extends BaseModel
{
    public function __construct()
    {
        $this->tableName = 'user';
    }

    public function checkExistsEmailUser($str)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT `email` FROM `{$this->tableName}` WHERE `email` LIKE '{$str}' AND `del_flag` =" . DEL_FLAG_0);
        return $arr->rowCount();
    }

    public function getInfoUserByEmail($str)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$this->tableName}` WHERE `email` LIKE '{$str}'");
        return $arr->fetch();
    }

    public function getInfoAdminByEmail($str)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `admin` WHERE `email` LIKE '{$str}'");
        return $arr->fetch();
    }

    public function getInfoUserByID($id)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$this->tableName}` WHERE `id` = '{$id}'");
        return $arr->fetch();
    }

    public function getIdUserByEmail($email)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT `id` FROM `{$this->tableName}` WHERE `email` = '{$email}'");
        return $arr->fetch();
    }
}
