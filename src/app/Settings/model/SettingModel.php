<?php


namespace app\Settings\model;


class SettingModel
{
    public function __construct(){
        $this->conn = \Database::GetConnection();
    }

    /**
     * @return Setting
     */
    public function Get($name){

        $stmt = $this->conn->prepare("SELECT * FROM setting WHERE name = ? LIMIT 1");
        $stmt->execute(array($name));

        if(!$stmt->rowCount()){
            return false;
        }
        $obj = $stmt->fetchObject();
        return new Setting($obj->name, $obj->value, $obj->description);

    }

    /**
     * @return Setting[]
     */
    public function GetAll(){
        $ret = array();

        $stmt = $this->conn->prepare("SELECT * FROM setting");
        $stmt->execute();

        while($obj = $stmt->fetchObject()){
            $ret[$obj->name] = new Setting($obj->name, $obj->value, $obj->description);
        }
        return $ret;
    }

    public function Save(Setting ...$settings){


        $update = $this->conn->prepare("UPDATE setting SET value = ? WHERE name = ?");
        $insert = $this->conn->prepare("INSERT INTO setting (value, name) VALUE(?,?)");

        foreach($settings as $setting){
            $stmt = $this->conn->prepare("SELECT * FROM setting WHERE name = ? LIMIT 1");
            $stmt->execute(array($setting->GetName()));

            if(!$stmt->rowCount()){
                $insert->execute(array($setting->GetValue(), $setting->GetName()));
            }else{
                $update->execute(array($setting->GetValue(), $setting->GetName()));
            }
        }
    }

    public function Install(){
        $this->conn->exec("
          CREATE TABLE IF NOT EXISTS `setting` (
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `value` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `type` varchar(100) COLLATE utf8_swedish_ci NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
        ");
    }

}