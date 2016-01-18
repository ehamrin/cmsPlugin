<?php
namespace app\Page;

/**
 * @Name Pages
 * @Description Add pages to you site
 * @Author Erik Hamrin
 * @Version v0.6
 * @Icon  fa-file-o
 */


class Page extends \app\AbstractPlugin
{
    private $model;
    private $view;
    protected $AdminController;
    protected $PublicController;

    public function __construct(\Application $application){
        parent::__construct($application);
        $this->model = new model\PageModel();
        $this->view = new view\Page($this->application, $this->model);

        $this->AdminController = new controller\AdminController($this->application, $this->model, $this->view);
        $this->PublicController = new controller\PublicController($this->application, $this->model, $this->view);
    }

    function Init($controller, $method="Index", ...$params){
        $parent = parent::Init($controller, $method, ...$params);

        if($parent == false && $this->HookRootAccess($method)){

            $parent = $this->PublicController->ViewCMS(strtolower($method), ...$params);
        }

        return $parent;
    }

    public function Install(){
        $this->model->Install();
    }

    public function UnInstall(){
        $this->RemoveTable('page');;
    }

    public function IsInstalled(){
        return $this->TableExists('page');
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
        return array(new \app\Authentication\model\Permission('Manage pages', 'manage-pages'));
    }

    public function HookPluginSettings(){
        return array(new \app\Settings\model\Setting('page-site-title', 'My site', 'The name of your website'));
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