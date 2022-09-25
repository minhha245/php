<?php
require_once "interface\DBInterface.php";
abstract class BaseModel implements DBInterface
{
    //CRUD
    public $tableName;
    public function getAll()
    {
        $db = DB::getInstance();
        $result = $db->query("SELECT * FROM `{$this->tableName}` where del_flag = " . DEL_FLAG_0);

        return $result->rowCount();
    }

    public function checkLogin($data)
    {
        $db = DB::getinstance();
        $stmt = $db->prepare(" SELECT * FROM $this->tableName WHERE email = ? AND password = ? AND del_flag =" . DEL_FLAG_0);
        $stmt->execute([$data['email'], $data['password']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (!empty($result)) ? $result : '';
    }

    public function insert($data = array())
    {
        $fields = "";
        $values = "";
        foreach ($data as $key => $value) {
            $fields .= "`" . $key . "`, ";
            $values .= "'" . $value . "', ";
        }

        $fields = substr($fields, 0, -2);
        $values = substr($values, 0, -2);

        $db = DB::getInstance();
        $sql = "INSERT INTO {$this->tableName} ($fields) VALUES($values)";
        $result = $db->query($sql);

        return (!empty($result)) ? true : false;
    }

    public function update($data = array(), $where = "")
    {
        $sql = "";
        foreach ($data as $field => $value) {
            $sql .= "{$field} = '{$value}', ";
        }
        $sql = substr($sql, 0, -2);

        $db = DB::getInstance();
        $sql = "UPDATE {$this->tableName} SET $sql WHERE $where";
        $result = $db->query($sql);

        return (!empty($result)) ? $result : '';
    }

    public function delete($where = "")
    {
        $db = DB::getInstance();
        $sql = "UPDATE {$this->tableName} SET `del_flag` = '" . DEL_FLAG_1 . "' WHERE $where";
        $result = $db->query($sql);

        return (!empty($result)) ? true : false;
    }

    public function getInfoSearch($data)
    {
        $db = DB::getInstance();
        $result = $db->query("SELECT * FROM `{$this->tableName}` WHERE `email` LIKE '%{$data['email']}%' AND `name` LIKE '%{$data['name']}%' AND `del_flag` = '" . DEL_FLAG_0 . "' ORDER BY `{$data['column']}` {$data['getSort']} LIMIT {$data['start']}, {$data['record_per_page']}");

        return $result->fetchAll();
    }

    public function getSearchAll($data)
    {
        $db = DB::getInstance();
        $result = $db->query("SELECT * FROM `{$this->tableName}` WHERE `email` LIKE '%{$data['email']}%' AND `name` LIKE '%{$data['name']}%' AND `del_flag` =" . DEL_FLAG_0);

        return $result->rowCount();
    }
}
