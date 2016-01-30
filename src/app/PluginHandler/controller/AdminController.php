<?php

namespace app\PluginHandler\controller;

use app\PluginHandler\model;
use app\PluginHandler\view;

class AdminController extends \app\Admin\AbstractAdminController
{

    public function __construct(\Application $application, model\PluginHandlerModel $model)
    {
        parent::__construct($application);
        $this->model = $model;
    }

    public function Index(...$params){

        $this->AuthorizeOrGoToAdmin("manage-plugin");

        $installedPlugin = $this->model->InstalledPlugins();
        $availablePlugin = $this->model->GetAvailablePlugins();
        $application = $this->application;

        return $this->View('admin.plugin_index', compact('installedPlugin', 'availablePlugin', 'application'));
    }

    public function post_Index(...$params)
    {
        $this->model->Save($this->getPluginPostArray());

        foreach($this->getPluginPostArray() as $plugin => $action){
            try{
                if($action == 'delete-data'){
                    $this->application->GetPlugin($plugin)->Uninstall();
                    $this->application->Remove($plugin);
                }elseif($action == 'Install'){
                    $this->application->InstallPlugin($plugin);
                }
            }catch(\Exception $e){
            }
        }

        $this->application->InvokeEvent('GenerateNewSitemap');

        setFlash('Plugins updated', 'success');
        $this->Reload();
    }

    private function getPluginPostArray()
    {
        $ret = array();
        foreach($_POST['plugin'] as $key => $plugin){
            if(isset($plugin['value'])){
                $ret[$key] = 'Install';
            }else{
                $ret[$key] = $plugin['action'];
            }
        }
        return $ret;
    }
}