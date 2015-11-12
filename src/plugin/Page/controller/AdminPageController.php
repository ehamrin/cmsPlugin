<?php


namespace plugin\Page\Controller;
use \plugin\Page\model;
use \plugin\Page\view;


class AdminPageController
{
    public function __construct(\Application $application, model\PageModel $model, view\Page $view){
        $this->application = $application;
        $this->view = $view;
        $this->model = $model;

    }


    public function Index(...$params){
        return $this->view->AdminList();
    }

    public function Add()
    {

        return $this->Edit(0);
    }

    public function Edit(\int $id)
    {
        if($this->view->UserSubmitted()){
            $this->model->Save($this->view->GetUpdatedPage($id));
            $this->application->InvokeEvent('GenerateNewSitemap');
            $this->view->EditSuccess($id);
        }
        return $this->view->Edit($id);
    }

    public function Delete($id)
    {
        $this->model->Delete($id);
        $this->application->InvokeEvent('GenerateNewSitemap');
        $this->view->GoToIndex();
    }

}