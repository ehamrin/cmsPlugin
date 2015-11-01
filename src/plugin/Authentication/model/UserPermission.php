<?php


namespace plugin\Authentication\model;


class UserPermission
{
    public function __construct($user, $permission){
        $this->user = $user;
        $this->permission = $permission;
    }

    public function GetUser(){
        return $this->user;
    }

    public function GetPermission(){
        return $this->permission;
    }

}