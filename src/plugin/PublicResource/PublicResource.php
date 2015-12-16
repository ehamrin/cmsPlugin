<?php


namespace plugin\PublicResource;

/**
 * @Name Public resource manager
 * @Description Manages public resources for plugins
 * @Author Erik Hamrin
 * @Version v0.5
 * @Icon fa-users
 */


class PublicResource implements \IPlugin
{

    private $model;
    private $view;
    private $controller;

    public function __construct(\Application $application){
        $this->application = $application;
        $this->model = new model\PublicResourceModel();
        $this->view = new view\PublicResourceView($this->application, $this->model);

        $this->controller = new controller\PublicResourceController($this->application, $this->model, $this->view);
    }

    function Init($method="Index", ...$params){
        if(method_exists($this->controller, $method)){
            return $this->controller->{$method}(...$params);
        }
        return false;
    }

    public function Index(...$params){
        return false;
    }

    public function Install(){}

    public function UnInstall(){}

    public function IsInstalled(){ return true; }

    public function HookRootAccess($method){
        $method = strtolower($method);
        if($method == 'css' || $method == 'js' || $method == 'json'){
            return true;
        }
        return false;
    }

}