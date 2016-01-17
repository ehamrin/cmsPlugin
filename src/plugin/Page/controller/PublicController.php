<?php

namespace plugin\Page\Controller;
use \plugin\Page\model;
use \plugin\Page\view;

class PublicController
{

    public function __construct(\Application $application, model\PageModel $model, view\Page $view){
        $this->view = $view;
        $this->model = $model;
        $this->application = $application;

    }

    public function ViewCMS($id = "", ...$params)
    {

        $this->application->InvokeEvent('NewVisitor');
        $page = $this->model->FindByURL($id);

        return $this->view->RenderCMS($page, ...$params);

    }
}