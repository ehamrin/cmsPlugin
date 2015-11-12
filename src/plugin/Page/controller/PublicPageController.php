<?php

namespace plugin\Page\Controller;
use \plugin\Page\model;
use \plugin\Page\view;

class PublicPageController
{

    public function __construct(\Application $application, model\PageModel $model, view\Page $view){
        $this->view = $view;
        $this->model = $model;

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
}