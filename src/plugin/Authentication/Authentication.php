<?php

namespace plugin\Authentication;

/**
 * @Name User Authentication
 * @Description Limit access to your admin panel by managing users
 * @Author Erik Hamrin
 * @Version v0.5
 * @Icon fa-users
 */

class Authentication implements \IPlugin, \plugin\Admin\IAdminPanel
{
    private $model;
    public function __construct(\Application $application){
        $this->application = $application;
        $this->model = new model\UserModel();
        $this->view = new view\User($application, $this->model);
        $this->adminController = new controller\AdminAuthenticationController($this->application, $this->model, $this->view);
    }

    function Init($method = "Index", ...$params)
    {
        if(method_exists($this, $method)){
            return $this->{$method}(...$params);
        }
        return false;
    }

    public function AdminPanelInit($method = "Index", ...$params)
    {
        if(method_exists($this->adminController, $method)) {
            return $this->adminController->{$method}(...$params);
        }
        return false;
    }

    public function Index(...$params)
    {
        return 'Authentication Index';
    }

    public function IsLoggedIn(){
        return $this->model->IsLoggedIn();
    }

    public function Login(){
        $login = $this->view->UserLoggedIn();
        if($login == false || !$this->model->Login($login)) {
            return $this->view->ShowLogin();
        }

        return true;
    }

    public function Logout(){
        $this->model->Logout();
    }

    public function Install(){
        $this->model->Install();
    }

    public function UnInstall(){
        $this->model->Uninstall();
    }

    public function IsInstalled(){
        return $this->model->IsInstalled();
    }

    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */

    public function HookAdminItems(){
        return array(
            new \NavigationItem('Users', 'user', array(new \NavigationItem('Add user', 'user/add', array(), 'manage-user')), 'manage-users',  'fa-users')
        );
    }

    public function HookAdminPanel(){
        if($this->application->GetUser()->Can('manage-users')){
            $users  = count($this->model->FetchAll());
            return <<<HTML
    <h1>Users</h1>
    <p>You currently have {$users} users</p>
HTML;
        }
        return null;
    }
}