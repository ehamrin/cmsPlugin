<?php

namespace plugin\Slider;

/**
 * @Name Image slider
 * @Description Add slides to your pages
 * @Author Erik Hamrin
 * @Icon  fa-image
 */

class Slider implements \IPlugin, \plugin\admin\IAdminPanel
{
    public function __construct(\Application $application){
        $this->application = $application;
        $this->view = new view\Slider();
    }


    function Init($method="Index", ...$params){
        return $this->Index();
    }

    public function AdminPanelInit($method = "Index", ...$params)
    {
        $method = 'Admin'.$method;

        if(method_exists($this, $method)) {
            return $this->{$method}(...$params);
        }
        return false;
    }

    public function Install(){
        //check for folder public/images/slider
        //make sure Apache can write to folder
        //Setup table
    }

    public function UnInstall(){
        //remove folder public/images/slider
        //Drop table
    }

    public function IsInstalled(){
        //check for folder public/images/slider
        //make sure Apache can write to folder
        //Setup table
        return true;
    }

    public function Index(...$params)
    {
        return 'Slider Index';
    }

    public function AdminIndex(...$params)
    {
        return 'Slider admin Index';
    }

    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */

    public function HookPageHeaderHTML(\plugin\Page\model\Page $page){
        return $this->view->RenderPageWidget();
    }

    public function HookAdminItems(){
        return array(
            new \NavigationItem(
                'Slider',
                'slider',
                array(),
                'manage-slider',
                'fa-image'
            )
        );
    }

    public function HookUserPermissions(){
        return array(new \plugin\Authentication\model\Permission('Manage slider', 'manage-slider'));
    }

    public function HookPluginSettings(){
        return array(new \plugin\Settings\model\Setting('slider-duration', '5', 'Seconds to display each slide'));
    }
}