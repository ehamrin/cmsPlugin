<?php


namespace plugin\Authentication\model;


class LoginCredential
{
    public function __construct($username, $password){
        $this->username = $username;
        $this->password = $password;
    }

    public function GetUsername(){
        return $this->username;
    }

    public function Getpassword(){
        return $this->password;
    }
}