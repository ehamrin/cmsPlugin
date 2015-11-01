<?php


namespace plugin\Authentication\model;


class User
{
    private $id;
    private $username;
    private $password;
    private $permissions;

    public function __construct($username, $password, $id = 0){
        $this->username = $username;
        if($id == 0){
            $password = password_hash($password, PASSWORD_BCRYPT);
        }
        $this->password = $password;
        $this->id = $id;
    }

    public function GetUsername(){
        return $this->username;
    }

    public function GetPassword(){
        return $this->password;
    }

    public function GetID(){
        return $this->id;
    }

    public function SetPassword($password){
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function AddPermission($permission){
        $this->permissions[$permission] = $permission;
    }

    public function Can($permission = ''){
        return isset($this->permissions[$permission]) || $this->id == 1 || $permission == '';
    }
}