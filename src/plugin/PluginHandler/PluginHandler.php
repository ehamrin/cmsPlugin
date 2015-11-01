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
        return $this->$method(...$params);
    }

    public function Index(...$params){
        return 'PluginHandler Index';
    }

    public function AdminIndex(...$params){
        if($this->view->WasSubmitted()){
                $this->model->Save($this->view->GetData());

                foreach($this->view->GetData() as $plugin => $action){
                    try{
                        $instance = $this->application->GetPlugin($plugin);
                        if($action == 'delete-data'){
                            $instance->Uninstall();
                        }
                    }catch(\Exception $e){
                    }
                }


                $this->view->Success();
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