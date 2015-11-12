<?php

namespace plugin\Settings;

/**
 * @Name Site settings
 * @Description Manage settings
 * @Author Erik Hamrin
 * @Version v0.6
 * @Icon  fa-cogs
 */

class Settings implements \IPlugin, \plugin\admin\IAdminPanel
{

    private $application;
    private $pluginSettings = null;

    public function __construct(\Application $application){
        $this->application = $application;
        $this->model = new model\SettingModel();
        $this->view = new view\Setting($this->application, $this->model);
    }


    function Init($method="Index", ...$params){
        return $this->AdminIndex();
    }

    public function Index(...$params)
    {
        return "Settings index";
    }

    public function AdminPanelInit($method = "Index", ...$params)
    {
        $method = 'Admin'.$method;

        if(method_exists($this, $method)) {
            return $this->{$method}(...$params);
        }
        return false;
    }

    public function AdminIndex(...$params)
    {
        if($this->view->WasSubmitted()){
            $this->model->Save(...$this->view->GetSettings());
            $this->view->EditSuccess();
        }

        return $this->view->ViewSettings($this->GetPluginSettings());
    }


    public function Install(){
        //Setup table
    }

    public function UnInstall(){
        //Drop table
    }

    public function IsInstalled(){
        //Setup table
        return true;
    }

    private function GetPluginSettings(){
        return $this->pluginSettings ?? $this->pluginSettings = $this->application->InvokeEvent('PluginSettings');
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
        return array(new \plugin\Authentication\model\Permission('Manage settings', 'manage-settings'));
    }
}