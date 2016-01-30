<?php

namespace app\Authentication;

/**
 * @Name User Authentication
 * @Description Limit access to your admin panel by managing users
 * @Author Erik Hamrin
 * @Version v0.5
 * @Icon fa-users
 */

class Authentication extends \app\AbstractPlugin
{
    public function __construct(\Application $application){
        parent::__construct($application);
    }

    public function IsLoggedIn(){
        return model\UserModel::IsLoggedIn();
    }

    public function Install(){
        model\UserModel::Install();
    }

    public function UnInstall(){
        model\UserModel::Uninstall();
    }

    public function IsInstalled(){
        return model\UserModel::IsInstalled();
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

    public function HookUserPermissions(){
        return array(
            new \app\Authentication\model\Permission('Manage users', 'manage-user'),
            new \app\Authentication\model\Permission('Manage permissions', 'manage-permissions')
        );
    }
}