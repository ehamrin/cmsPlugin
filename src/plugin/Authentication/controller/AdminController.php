<?php


namespace plugin\Authentication\controller;

use \plugin\Authentication\model;
use \plugin\Authentication\view;

class AdminController
{

    public function __construct(\Application $application){
        $this->model = new model\UserModel();
        $this->view = new view\User($application, $this->model);
    }

    public function Index()
    {
        return $this->view->AdminList();
    }

    public function Add()
    {
        if($this->view->UserSubmittedAddForm()){
            $this->model->Create($this->view->GetNewUser());
            $this->view->AddSuccess();
        }
        return $this->view->Add();
    }

    public function Edit(\int $id)
    {
        if($this->view->UserSubmittedEditForm()){
            $this->model->Update($this->view->GetUpdatedUser($id));
            $this->view->EditSuccess();
        }elseif($this->view->UserSubmittedEditPassword()){
            $this->model->UpdatePassword($this->view->GetUpdatedPassword($id));
            $this->view->PasswordSuccess();
        }
        return $this->view->Edit($id);
    }

    public function View(\string $id)
    {
        if($id != ""){
            $this->model->FindByID($id);
        }
        return "User view";
    }

    public function Delete($id)
    {
        $this->model->Delete($id);
        $this->view->GoToIndex();
    }

}