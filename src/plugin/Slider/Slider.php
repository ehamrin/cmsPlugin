<?php

namespace plugin\Slider;
use annotation\repository\PDORepositoryFactory;

/**
 * @Name Image slider
 * @Description Add slides to your pages
 * @Author Erik Hamrin
 * @Icon  fa-image
 */

class Slider extends \app\AbstractPlugin
{
    private $view;
    protected $AdminController;
    private $repository;

    public function __construct(\Application $application){
        parent::__construct($application);
        $this->repository = PDORepositoryFactory::get("\\plugin\\Slider\\model\\Slide", \Database::GetConnection());
        $this->view = new view\Slider($this->application);
        $this->AdminController = new controller\AdminController($this->application, $this->repository, $this->view);
    }


    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */

    public function HookPageHeaderHTML(\app\Page\model\Page $page){
        return $this->view->RenderPageWidget($this->repository->findAll());
    }

    public function HookAdminItems(){
        return array(
            new \NavigationItem(
                'Slider',
                'slider',
                array(new \NavigationItem(
                    'Add slide',
                    'slider/create',
                    array(),
                    'manage-slider',
                    'fa-image'
                )),
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

    public function Install()
    {
        $this->repository->checkTable();
    }

    public function IsInstalled()
    {
        return $this->TableExists("slide");
    }

    public function Uninstall()
    {
        $this->repository->uninstall();
    }
}