<?php


namespace app\Authentication\controller;

use \app\Authentication\model;
use \app\Authentication\view;

class PublicController
{

    public function __construct(\Application $application){
        $this->model = new model\UserModel();
        $this->view = new view\User($application, $this->model);
    }

    public function Login(){
        $login = $this->view->UserLoggedIn();
        if($login == false || !$this->model->Login($login)) {
            return $this->view->ShowLogin();
        }

        return true;
    }

    public function Logout(){
        model\UserModel::Logout();
        return $this->Login();
    }

}