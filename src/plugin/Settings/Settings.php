<?php
/*
 * Name: Site settings
 * Description: Manage settings
 * Author: Erik Hamrin
 * Version: v0.6
 * Icon:  fa-cogs
 */

namespace plugin\Settings;


class Settings implements \IPlugin
{

    private $application;

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

    public function AdminIndex(...$params)
    {
        if($this->view->WasSubmitted()){
            $this->model->Save(...$this->view->GetSettings());
            $this->view->EditSuccess();
        }
        return $this->view->ViewSettings();
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
                'manage-setting'
            )
        );
    }

    public function HookUserPermissions(){
        return array(new \plugin\Authentication\model\Permission('Manage settings', 'manage-settings'));
    }
}