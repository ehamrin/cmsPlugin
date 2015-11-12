<?php


namespace plugin\Settings\model;


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
            $ret[] = new Setting($obj->name, $obj->value, $obj->description);
        }
        return $ret;
    }

    public function Save(Setting ...$settings){
        $stmt = $this->conn->prepare("UPDATE setting SET value = ? WHERE name = ?");

        foreach($settings as $setting){
            $stmt->execute(array($setting->GetValue(), $setting->GetName()));
        }
    }

}