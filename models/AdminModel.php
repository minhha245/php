<?php
require_once "models/BaseModel.php";
class AdminModel extends BaseModel
{   
    function __construct()
    {
         $this->tableName = 'admin';
    }

    public function checkExistsEmailAdmin($str)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT `email` FROM `{$this->tableName}` WHERE `email` LIKE '{$str}' AND `del_flag` =". DEL_FLAG_0);

        return $arr->rowCount();
    }

    public function getInfoAdmin($id)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT * FROM `{$this->tableName}` WHERE `id` = '{$id}'");

        return $arr->fetch();
    }

    public function getRoleAdmin($email)
    {
        $db = DB::getInstance();
        $arr = $db->query("SELECT `role_type` FROM `{$this->tableName}` WHERE `email` = '{$email}' AND `del_flag` =". DEL_FLAG_0);

        return $arr->fetch();
    }
    
    public function getIdAdmin($str)
    {   
        $db = DB::getInstance();
        $arr = $db->query("SELECT `id` FROM `{$this->tableName}` WHERE `email` LIKE '{$str}'"); 
        
        return $arr->fetch();
    }
}