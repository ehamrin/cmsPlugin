<?php


namespace plugin\Authentication\view;

use \plugin\Authentication\model;

class User
{
    private $userToEdit = 0;
    public function __construct(\Application $application, model\UserModel $model){
        $this->application = $application;
        $this->model = $model;
        $this->editForm = $this->CreateEditForm();
        $this->newForm = $this->CreateNewForm();
    }

    private function CreateEditForm(){
        $form = new \Form\controller\FormController("EditUser");

        $permissions = array();
        $ownPermission = array(new model\Permission('Manage users', 'manage-users'), new model\Permission('Manage permissions', 'manage-permissions'));
        foreach ($this->application->InvokeEvent("UserPermissions") as $event) {
            /* @var $event \Event */

            foreach ($event->GetData() as $permission) {
                /* @var $permission model\Permission */
                $permissions[] = (new \Form\model\input\Checkbox("permission[" . $permission->GetPermission() . "]"))
                    ->SetLabel($permission->GetName());
            }
        }

        foreach ($ownPermission as $permission) {
            $permissions[] = (new \Form\model\input\Checkbox("permission[" . $permission->GetPermission() . "]"))
                ->SetLabel($permission->GetName());
        }

        $form->AddInput(
            (new \Form\model\input\Text("username"))
                ->SetLabel("Username")
                ->SetValidation(new \Form\model\validation\Required("You must enter a password")),
            ...$permissions
        );
        $form->AddInput((new \Form\model\input\Submit("submit", "Submit")));
        return $form;
    }

    private function CreateNewForm(){
        $form = new \Form\controller\FormController("EditUser");

        $permissions = array();
        $ownPermission = array(new model\Permission('Manage users', 'manage-users'), new model\Permission('Manage permissions', 'manage-permissions'));
        foreach ($this->application->InvokeEvent("UserPermissions") as $event) {
            /* @var $event \Event */

            foreach ($event->GetData() as $permission) {
                /* @var $permission model\Permission */
                $permissions[] = (new \Form\model\input\Checkbox("permission[" . $permission->GetPermission() . "]"))
                    ->SetLabel($permission->GetName());
            }
        }

        foreach ($ownPermission as $permission) {
            $permissions[] = (new \Form\model\input\Checkbox("permission[" . $permission->GetPermission() . "]"))
                ->SetLabel($permission->GetName());
        }

        $form->AddInput(
            (new \Form\model\input\Text("username"))
                ->SetLabel("Username")
                ->SetValidation(new \Form\model\validation\Required("You must enter a password")),
            (new \Form\model\input\Password("password"))
                ->SetLabel("Password")
                ->SetValidation(new \Form\model\validation\Required("You must enter a password"))
                ->SetComparator(new \Form\model\comparator\EqualTo("passwordRepeat", "The passwords must match")),
            (new \Form\model\input\Password("passwordRepeat"))
                ->SetLabel("Repeat Password")
                ->SetComparator(new \Form\model\comparator\EqualTo("password", "The passwords must match"))
                ->SetValidation(new \Form\model\validation\Required("You must enter a password")),
            ...$permissions
        );
        $form->AddInput((new \Form\model\input\Submit("submit", "Submit")));
        return $form;
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

    public function Edit(){

        if($this->userToEdit > 0){
            $user = $this->model->FindByID($this->userToEdit);
            /* @var $user \plugin\Authentication\model\User */
            $this->editForm->UpdateValue("username", $user->GetUsername());
            return $this->editForm->GetView();
        }

        return $this->newForm->GetView();
    }

    public function UserSubmitted(){
        $submitted = $this->editForm->WasSubmitted() || $this->editForm->WasSubmitted();
        if($submitted){/*
            if($this->model->UserExists($this->GetUpdatedUser())){
                $this->editForm->InjectFormError("The user already exists!");
                return false;
            }*/
            return true;
        }
        return false;
    }

    public function SetUser($userID){
        $this->userToEdit = $userID;
    }

    public function GetUpdatedUser(){
        if($this->userToEdit > 0) {
            $data = $this->editForm->GetData();
            $user = new model\User($data['username'], '', $this->userToEdit);
        }else{
            $data = $this->newForm->GetData();
            $user = new model\User($data['username'], '', $this->userToEdit);
            $user->SetPassword($data['password']);
        }
        return $user;
    }

    public function EditSuccess(){
        if($this->userToEdit > 0){
            $this->editForm->InjectFormSuccess('<a class="confirmed-add" href="/admin/user">You edited a user!</a>');
        }else{
            $this->editForm->InjectFormSuccess('<a class="confirmed-add" href="/admin/user">You added a user!</a>');
        }
    }


    public function GoToIndex(){
        header('Location: /admin/user');
        die();
    }

    public function ShowLogin(){
        return '
        <div id="login_form">
            <form method="POST" >
                <input type="text" name="username" placeholder="Username" />
                <input type="password" name="password" placeholder="Password" />
                <input type="submit" class="button" name="login" value="Log in" />
            </form>
        </div>
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