<?php


namespace app\Authentication\controller;

use \app\Authentication\model;
use \app\Authentication\view;

class AdminController extends \app\Admin\AbstractAdminController
{

    public function __construct(\Application $application){
        parent::__construct($application);
        $this->model = new model\UserModel();
        $this->view = new view\User($application, $this->model);
    }

    public function Index()
    {
        $this->AuthorizeOrGoToAdmin("manage-user");

        return $this->view->AdminList();
    }

    public function Add()
    {
        $this->AuthorizeOrGoToAdmin("manage-user");

        if($this->view->UserSubmittedAddForm()){
            $this->model->Create($this->view->GetNewUser());
            $this->view->AddSuccess();
        }
        return $this->view->Add();
    }

    public function Edit(\int $id)
    {
        $this->AuthorizeOrGoToAdmin("manage-user");

        if($this->view->UserSubmittedEditForm()){
            $this->model->Update($this->view->GetUpdatedUser($id));
            $this->view->EditSuccess();
        }elseif($this->view->UserSubmittedEditPassword()){
            $this->model->UpdatePassword($this->view->GetUpdatedPassword($id));
            $this->view->PasswordSuccess();
        }
        return $this->view->Edit($id);
    }

    public function Delete($id)
    {
        $this->AuthorizeOrGoToAdmin("manage-user");

        $this->model->Delete($id);
        $this->view->GoToIndex();
    }

}