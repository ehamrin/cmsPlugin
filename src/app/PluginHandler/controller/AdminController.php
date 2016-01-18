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
        $this->view = new view\PluginHandler($this->model);
    }

    public function Index(...$params){

        $this->AuthorizeOrGoToAdmin("manage-plugin");

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