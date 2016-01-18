<?php


namespace app\Settings\controller;

use \app\Settings\model;
use \app\Settings\view;

class AdminController extends \app\Admin\AbstractAdminController
{
    public function __construct(\Application $application, model\SettingModel $model, view\Setting $view){
        parent::__construct($application);
        $this->view = $view;
        $this->model = $model;
    }

    private $pluginSettings = null;

    private function GetPluginSettings(){
        return $this->pluginSettings ?? $this->pluginSettings = $this->application->InvokeEvent('PluginSettings');
    }

    public function Index(){

        $this->AuthorizeOrGoToAdmin("manage-settings");

        if($this->view->WasSubmitted()){
            $this->model->Save(...$this->view->GetSettings());
            $this->view->EditSuccess();
        }

        return $this->view->ViewSettings($this->GetPluginSettings());
    }
}