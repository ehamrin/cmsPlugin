<?php
/*
 * Name: Plugin Manager
 * Description: Choose what plugins will be active
 * Author: Erik Hamrin
 * Version: v0.5
 * Icon: fa-puzzle-piece
 */

namespace plugin\PluginHandler;


class PluginHandler implements \IPlugin
{
    private $model;
    private $view;
    private $application;


    public function __construct(\Application $application){
        $this->application = $application;
        $this->model = new model\PluginHandlerModel();
        $this->view = new view\PluginHandler($this->model);
    }

    function Init($method="Index", ...$params){
        return $this->Index(...$params);
    }

    public function Index(...$params){
        if($this->view->WasSubmitted()){
            try{
                $this->model->Save($this->view->GetData());
                $this->view->Success();
            }catch(\Exception $e){

            }

        }
        return $this->view->AdminList($this->application);
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
        return array(new \NavigationItem('Manage Plugins', 'plugin', array(), 'manage-plugin'));
    }
}