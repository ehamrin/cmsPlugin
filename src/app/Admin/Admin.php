<?php

namespace app\Admin;

/**
 * @Name Admin panel
 * @Description Allow managing of the content on the site
 * @Author Erik Hamrin
 * @Version v0.5
 */

class Admin extends \app\AbstractPlugin
{
    public function __construct(\Application $application){
        parent::__construct($application);
        $this->view = new view\Admin($this->application);
    }

    function Init($controller, $method="Index", ...$params){

        if(!$this->application->IsAuthenticated()){
            $login= $this->application->GetPlugin('Authentication')->Init('Public', 'Login');
            if($login !== true){
                header("HTTP/1.1 401 Unauthorized");
                return $login;
            }
        }

        if($method === 'Admin'){
            $method = array_shift($params);
            $method = $method === null ? 'Index' : $method;
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
                        $instance = $event->GetHookListener();
                        return $this->view->Render($instance->Init("Admin", ...$params));
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
        $this->application->GetPlugin('Authentication')->Init('Public', 'Logout');
        $this->view->GoToIndex();
    }

    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */
    public function HookRootAccess($method){
        return (strtolower($method) == 'admin');
    }
}