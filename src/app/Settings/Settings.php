<?php

namespace app\Settings;

/**
 * @Name Site settings
 * @Description Manage settings
 * @Author Erik Hamrin
 * @Version v0.6
 * @Icon  fa-cogs
 */

class Settings extends \app\AbstractPlugin
{

    public function __construct(\Application $application){
        parent::__construct($application);
        $this->model = new model\SettingModel();
        $this->view = new view\Setting($this->application, $this->model);
        $this->AdminController = new controller\AdminController($application, $this->model, $this->view);
    }

    public function Install()
    {
        $this->model->Install();
    }

    public function IsInstalled()
    {
        return $this->TableExists('setting');
    }
    public function Uninstall()
    {
        $this->RemoveTable('setting');
    }

    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */

   public function HookAdminItems(){

        return array(
            new \NavigationItem(
                'Settings',
                'setting',
                array(),
                'manage-setting',
                'fa-cogs'
            )
        );
    }

    public function HookUserPermissions(){
        return array(new \app\Authentication\model\Permission('Manage settings', 'manage-settings'));
    }
}