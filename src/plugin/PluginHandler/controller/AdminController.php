<?php

namespace plugin\PluginHandler\controller;

use plugin\PluginHandler\model;
use plugin\PluginHandler\view;

class AdminController
{

    public function __construct(\Application $application, model\PluginHandlerModel $model)
    {
        $this->application = $application;
        $this->model = $model;
        $this->view = new view\PluginHandler($this->model);
    }

    public function Index(...$params){
        if($this->view->WasSubmitted()){
            $this->model->Save($this->view->GetData());

            foreach($this->view->GetData() as $plugin => $action){
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

            $this->view->Success();
        }
        return $this->view->AdminList($this->application);
    }
}