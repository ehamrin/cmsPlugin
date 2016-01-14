<?php
namespace plugin\Page;

/**
 * @Name Pages
 * @Description Add pages to you site
 * @Author Erik Hamrin
 * @Version v0.6
 * @Icon  fa-file-o
 */


class Page implements \IPlugin, \plugin\Admin\IAdminPanel
{
    private $model;
    private $view;
    private $adminController;
    private $publicController;

    public function __construct(\Application $application){
        $this->application = $application;
        $this->model = new model\PageModel();
        $this->view = new view\Page($this->application, $this->model);

        $this->adminController = new controller\AdminPageController($this->application, $this->model, $this->view);
        $this->publicController = new controller\PublicPageController($this->application, $this->model, $this->view);
    }

    function Init($method="Index", ...$params){
        if(!method_exists($this, $method) && $this->HookRootAccess($method)){
            return $this->publicController->ViewCMS(strtolower($method), ...$params);
        }elseif(method_exists($this->publicController, $method)){
            return $this->publicController->{$method}(...$params);
        }
        return false;
    }

    public function AdminPanelInit($method = "Index", ...$params)
    {
        if(method_exists($this->adminController, $method)) {
            return $this->adminController->{$method}(...$params);
        }

        return false;
    }

    public function Index(...$params){
        return 'PageIndex';
    }

    public function Install(){
        $this->model->Install();
    }

    public function UnInstall(){
        $this->model->Uninstall();
    }

    public function IsInstalled(){
        return $this->model->IsInstalled();
    }


    /*
     * ------------------------------------------------------
     * Hooks
     * ------------------------------------------------------
     */
    public function HookRootAccess($method){
        return in_array(strtolower($method), $this->model->FetchAllSlugs());
    }

    public function HookAdminItems(){
        return array(
            new \NavigationItem(
                'Pages',
                'page',
                array(
                    new \NavigationItem('Add page', 'page/add', array(), 'manage-pages')
                ),
                'manage-pages',
                'fa-file-o'
            )
        );
    }

    public function HookAdminPanel(){
        if($this->application->GetUser()->Can('manage-pages')){
            $pages = count($this->model->FetchAll());
            return <<<HTML
    <h1>Pages</h1>
    <p>You currently have {$pages} pages</p>
HTML;
        }
        return null;
    }

    public function HookUserPermissions(){
        return array(new \plugin\Authentication\model\Permission('Manage pages', 'manage-pages'));
    }

    public function HookPluginSettings(){
        return array(new \plugin\Settings\model\Setting('page-site-title', 'My site', 'The name of your website'));
    }

    public function HookGenerateSitemap(){
        $ret = '';
        foreach($this->model->FetchAllSlugs() as $slug){

            $ret .= '
            <url>
                <loc>https://' . $_SERVER['SERVER_NAME'] . '/' . $slug . '</loc>
            </url>';
        }

        return $ret;
    }


}