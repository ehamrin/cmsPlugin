<?php

namespace plugin\Slider;

/**
 * @Name Image slider
 * @Description Add slides to your pages
 * @Author Erik Hamrin
 * @Icon  fa-image
 */

class Slider extends \app\AbstractPlugin
{
    public function __construct(\Application $application){
        parent::__construct($application);
        $this->view = new view\Slider();
    }


    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */

    public function HookPageHeaderHTML(\app\Page\model\Page $page){
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
        return array(new \app\Authentication\model\Permission('Manage slider', 'manage-slider'));
    }

    public function HookPluginSettings(){
        return array(new \app\Settings\model\Setting('slider-duration', '5', 'Seconds to display each slide'));
    }
}