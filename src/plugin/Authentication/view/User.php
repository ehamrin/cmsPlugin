<?php


namespace plugin\Authentication\view;

use \plugin\Authentication\model;

class User extends \plugin\AbstractView
{

    protected $message = null;
    public function __construct(\Application $application, model\UserModel $model){
        $this->application = $application;
        $this->model = $model;
    }

    public function AdminList(){
        $rows = "";

        foreach($this->model->FetchAll() as $user){
            $rows .= '
            <tr>
                <td>' . $user->GetUsername() . '</td>
                <td><a href="/admin/user/edit/' . $user->GetID() . '" class="edit"><i class="fa fa-pencil-square-o"></i></a></td>
                <td>' . ($user->GetID() > 1 ? '<a href="/admin/user/delete/' . $user->GetID() . '" class="delete"><i class="fa fa-trash"></i></a>' : '') . '</td>
            </tr>' . PHP_EOL;
        }

        return <<<HTML
    <table>
        <tr>
            <th>Username</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        {$rows}
    </table>
HTML;
    }

    public function Edit($id){
        $user = $this->model->FindByID($id);

        $permissions = array(new model\Permission('Manage users', 'manage-users'), new model\Permission('Manage permissions', 'manage-permissions'));

        foreach ($this->application->InvokeEvent("UserPermissions") as $event) {
            /* @var $event \Event */

            foreach ($event->GetData() as $permission) {
                /* @var $permission model\Permission */
                $permissions[] = $permission;
            }
        }
        return $this->View('Edit', array('permissions' => $permissions, 'user' => $user));
    }

    public function Add(){

        $permissions = array(new model\Permission('Manage users', 'manage-users'), new model\Permission('Manage permissions', 'manage-permissions'));

        foreach ($this->application->InvokeEvent("UserPermissions") as $event) {
            /* @var $event \Event */

            foreach ($event->GetData() as $permission) {
                /* @var $permission model\Permission */
                $permissions[] = $permission;
            }
        }
        return $this->View('Add', array('permissions' => $permissions));
    }

    public function UserSubmittedEditForm(){
        return isset($_POST['edit_submit']);
    }


    public function UserSubmittedAddForm(){
        return isset($_POST['add_submit']);
    }

    public function UserSubmittedEditPassword(){
        return isset($_POST['edit_password']) && $_POST['new'] == $_POST['new_repeat'];
    }

    public function GetUpdatedPassword($id){
        $user = new model\User('', $_POST['new'], $id);
        $user->SetPassword($_POST['new']);
        return $user;
    }

    public function GetNewUser(){
        $user = new model\User($_POST['username'], $_POST['password'], 0);
        if(isset($_POST['permission'])){
            foreach($_POST['permission'] as $permission){
                $user->AddPermission($permission);
            }
        }

        return $user;
    }

    public function GetUpdatedUser($id){
        $user = new model\User($_POST['username'], '', $id);
        if(isset($_POST['permission'])){
            foreach($_POST['permission'] as $permission){
                $user->AddPermission($permission);
            }
        }

        return $user;
    }

    public function PasswordSuccess(){
        $this->message = '<a class="confirmed-add" href="/admin/user">You edited a users password!</a>';
    }

    public function EditSuccess(){
        $this->message = '<a class="confirmed-add" href="/admin/user">You edited a user!</a>';
    }

    public function AddSuccess(){
        $this->message = '<a class="confirmed-add" href="/admin/user">You added a new user!</a>';
    }


    public function GoToIndex(){
        header('Location: /admin/user');
        die();
    }

    public function ShowLogin(){
        return '
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/admin.css?v=1.0">
</head>
<body>
        <div id="login_form">
            <form method="POST" >
                <input type="text" name="username" placeholder="Username" />
                <input type="password" name="password" placeholder="Password" />
                <input type="submit" class="button" name="login" value="Log in" />
            </form>
        </div>
        </body>
</html>
        ';
    }

    public function UserLoggedIn(){
        if(isset($_POST['login'])){
            return new model\LoginCredential($_POST['username'], $_POST['password']);
        }else{
            return false;
        }
    }
}