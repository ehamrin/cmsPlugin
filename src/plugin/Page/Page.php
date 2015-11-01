<?php
/*
 * Name: Pages
 * Description: Add pages to you site
 * Author: Erik Hamrin
 * Version: v0.6
 * Icon:  fa-file-o
 */

namespace plugin\Page;


class Page implements \IPlugin
{
    private $model;
    private $view;

    public function __construct(\Application $application){
        $this->application = $application;
        $this->model = new model\PageModel();
        $this->view = new view\Page($this->application, $this->model);
    }

    function Init($method="Index", ...$params){

        if(!method_exists($this, $method) && $this->HookRootAccess($method)){
            return $this->ViewCMS(strtolower($method));
        }elseif(method_exists($this, $method)){
            return $this->{$method}(...$params);
        }
        return false;
    }

    public function Index(...$params){
        return 'PageIndex';
    }

    public function AdminIndex(...$params){
        return $this->view->AdminList();
    }

    public function AdminAdd()
    {

        return $this->AdminEdit(0);
    }

    public function AdminEdit(\int $id)
    {
        if($this->view->UserSubmitted()){
            $this->model->Save($this->view->GetUpdatedPage($id));
            $this->view->EditSuccess($id);
        }
        return $this->view->Edit($id);
    }

    public function AdminDelete($id)
    {
        $this->model->Delete($id);
        $this->view->GoToIndex();
    }

    public function ViewCMS($id = "")
    {
        try{
            $page = $this->model->FindByURL($id);
            return $this->view->RenderCMS($page);
        }catch(\Exception $e){
            return false;
        }
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
                'manage-pages'
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
}