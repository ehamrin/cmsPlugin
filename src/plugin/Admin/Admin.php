<?php
/*
 * Name: Admin panel
 * Description: Allow managing of the content on the site
 * Author: Erik Hamrin
 * Version: v0.5
 */

namespace plugin\Admin;


class Admin implements \IPlugin
{
    private $application;

    public function __construct(\Application $application){
        $this->application = $application;
        $this->view = new view\Admin($this->application);
    }

    function Init($method="Index", ...$params){

        if($method === 'Admin'){
            $method = array_shift($params);
            $method = $method === null ? 'Index' : $method;
        }

        if(!$this->application->IsAuthenticated()){
            $login= $this->application->GetPlugin('Authentication')->Init('AdminLogin');
            if($login !== true){
                return $this->view->Render($login);
            }
        }

        if(method_exists($this, $method)){
           return $this->view->Render($this->{$method}());
        }

        $listeners = $this->application->InvokeEvent("AdminItems");
        if(count($listeners)){
            foreach($listeners as $event){
                /* @var $event \Event */
                foreach($event->GetData() as $navitem){
                    /* @var $navitem \NavigationItem */
                    if($method == $navitem->GetLink()){
                        $method = array_shift($params);
                        $method = 'Admin' . (empty($method) ? 'Index' : $method);
                        return $this->view->Render($event->GetHookListener()->Init($method, ...$params));
                    }
                }

            }
        }

        return false;
    }

    public function Index(...$params){
        return $this->view->AdminPanel();
    }

    public function Logout(...$params){
        $login= $this->application->GetPlugin('Authentication')->Init('Logout');
        $this->view->GoToIndex();
    }

    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */
    public function HookRootAccess($method){
        if(strtolower($method) == 'admin'){
            return true;
        }

        return false;

    }

    public function Install(){
    }

    public function UnInstall(){
    }

    public function IsInstalled(){
        return true;
    }


}