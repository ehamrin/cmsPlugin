<?php


namespace app\Authentication\controller;

use app\Admin\AbstractAdminController;
use \app\Authentication\model;
use \app\Authentication\view;

class AdminController extends AbstractAdminController
{

    public function __construct(\Application $application){
        parent::__construct($application);
        $this->model = new model\UserModel();
    }

    public function Index()
    {
        $this->AuthorizeOrGoToAdmin("manage-user");

        $users = $this->model->FetchAll();
        return $this->View('user_index', compact('users'));
    }

    public function Add()
    {
        $this->AuthorizeOrGoToAdmin("manage-user");
        $user = new model\User('', '', 0);

        $permissions = $this->getPermissions();
        return $this->view('user_add', compact('permissions', 'user'));
    }

    public function post_Add()
    {
        $user = new model\User($_POST['username'], $_POST['password'], 0);

        if(isset($_POST['permission']) && is_array($_POST['permission'])){
            foreach($_POST['permission'] as $permission){
                $user->AddPermission($permission);
            }
        }

        if($this->model->Create($user)){
            setFlash('You added a new user!', 'success');

            $this->Redirect('/admin/user');
        }
        setFlash('There was an error adding the user', 'error');
        $permissions = $this->getPermissions();
        return $this->view('user_add', compact('permissions', 'user'));
    }

    public function Edit(\int $id)
    {
        $this->AuthorizeOrGoToAdmin("manage-user");

        $user = $this->model->FindByID($id);

        if($this->requestMethod() == 'POST' && isset($_POST['username'])){
            $this->UpdateUserInfo($id);
        }elseif(isset($_POST['edit_password']) && $_POST['new'] == $_POST['new_repeat']){
            $user->SetPassword($_POST['new']);

            $this->model->UpdatePassword($user);

            setFlash("You've updated the password for " . htmlspecialchars($user->GetUsername()), 'success');
            $this->Redirect('/admin/user/edit/' . intval($id));
        }

        $permissions = $this->getPermissions();

        return $this->View('user_edit', compact('user', 'permissions'));
    }

    public function delete_Delete($id)
    {
        $this->AuthorizeOrGoToAdmin("manage-user");

        $this->model->Delete($id);
        $this->Redirect('admin/user');
    }

    private function getPermissions()
    {
        $permissions = array();

        foreach ($this->application->InvokeEvent("UserPermissions") as $event) {
            /* @var $event \Event */

            foreach ($event->GetData() as $permission) {
                /* @var $permission model\Permission */
                $permissions[] = $permission;
            }
        }

        return $permissions;
    }

    private function UpdateUserInfo($id)
    {
        $user = new model\User($_POST['username'], '', $id);
        if(isset($_POST['permission']) && is_array($_POST['permission'])){
            foreach($_POST['permission'] as $permission){
                $user->AddPermission($permission);
            }
        }
        $this->model->Update($user);

        setFlash("You've edited " . htmlspecialchars($user->GetUsername()), 'success');
        $this->Redirect('/admin/user');
    }

}