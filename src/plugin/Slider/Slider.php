<?php
/*
 * Name: Image slider
 * Description: Add slides to your pages
 * Author: Erik Hamrin
 * Icon:  fa-image
 */


namespace plugin\Slider;


class Slider implements \IPlugin
{
    public function __construct(\Application $application){
        $this->application = $application;
        $this->view = new view\Slider();
    }


    function Init($method="Index", ...$params){
        return $this->Index();
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
                'manage-slider'
            )
        );
    }

    public function HookUserPermissions(){
        return array(new \plugin\Authentication\model\Permission('Manage slider', 'manage-slider'));
    }
}