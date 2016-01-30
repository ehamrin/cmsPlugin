<?php


namespace app\Page\Controller;
use \app\Page\model;
use \app\Page\view;


class AdminController extends \app\Admin\AbstractAdminController
{
    public function __construct(\Application $application, model\PageModel $model, view\Page $view){
        parent::__construct($application);
        $this->view = $view;
        $this->model = $model;

    }

    public function Index(...$params){
        $this->AuthorizeOrGoToAdmin("manage-pages");

        return $this->view->AdminList();
    }

    public function Add()
    {
        $this->AuthorizeOrGoToAdmin("manage-pages");
        return $this->Edit(0);
    }

    public function Edit(\int $id)
    {
        $this->AuthorizeOrGoToAdmin("manage-pages");
        if($this->view->UserSubmitted()){
            $this->model->Save($this->view->GetUpdatedPage($id));
            $this->application->InvokeEvent('GenerateNewSitemap');
            $this->view->EditSuccess($id);
        }
        return $this->view->Edit($id);
    }

    public function delete_Delete($id)
    {
        $this->AuthorizeOrGoToAdmin("manage-pages");

        $this->model->Delete($id);
        $this->application->InvokeEvent('GenerateNewSitemap');
        $this->view->GoToIndex();
    }

}