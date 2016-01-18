<?php

namespace app\PluginHandler;

/**
 * @Name Plugin Manager
 * @Description Choose what plugins will be active
 * @Author Erik Hamrin
 * @Version v0.5
 * @Icon fa-puzzle-piece
 */

class PluginHandler extends \app\AbstractPlugin
{
    private $model;
    protected $AdminController;

    public function __construct(\Application $application){
        parent::__construct($application);
        $this->model = new model\PluginHandlerModel();
        $this->AdminController = new controller\AdminController($application, $this->model);
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
    public function HookActivatedPlugins($availableitems){
        $this->model->SetAvailablePlugins($availableitems);
        return $this->model->InstalledPlugins();
    }

    public function HookAdminItems(){
        return array(new \NavigationItem('Manage Plugins', 'plugin', array(), 'manage-plugin', 'fa-puzzle-piece'));
    }
}