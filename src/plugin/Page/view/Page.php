<?php


namespace plugin\Page\view;

use \plugin\Page\model;

class Page extends \plugin\AbstractView
{
    private $settingModel;
    public function __construct(\Application $application, model\PageModel $model){
        $this->application = $application;
        $this->model = $model;
        $this->editForm = $this->CreateEditForm();
    }

    private function CreateEditForm(){
        $form = new \Form\controller\FormController("EditPage");


        $form->AddInput(
            (new \Form\model\input\Text("title"))
                ->SetLabel("Title"),
            (new \Form\model\input\Textarea("content"))
                ->SetAttributes(new \Form\model\Option("class", "tinyMCE")),

            (new \Form\model\input\Submit("submit", "Submit"))
        );
        return $form;
    }

    public function RenderCMS(model\Page $page){
        $headerHook = '';
        foreach ($this->application->InvokeEvent("PageHeaderHTML", $page) as $event) {
            $headerHook .= $event->GetData();
        }
        return $this->View('CMSPage', array('page' => $page, 'headerHook' => $headerHook));

    }

    protected function RenderNav(model\Page $current){

        $ret = "";
        foreach($this->model->FetchAll() as $page){
            $currentItem = $page->GetSlug() == $current->GetSlug() ? ' class="current"' : '';
            $ret .= '<li' . $currentItem . '><a href="/' . $page->GetSlug() . '">' . $page->GetName() . '</a></li>';
        }

        $ret = '<ul>' . $ret . '</ul>';

        return $ret;
    }

    public function AdminList(){
        return $this->View('AdminList');
    }

    public function Edit($pageID){
        if($pageID > 0){
            $page = $this->model->FindByID($pageID);
            $this->editForm->UpdateValue("title", $page->GetName());
            $this->editForm->UpdateValue("content", $page->GetContent());
        }
        return $this->editForm->GetView();
    }

    public function UserSubmitted(){
        return $this->editForm->WasSubmitted();
    }

    public function GetUpdatedPage($pageID){
        $data = $this->editForm->GetData();
        return new model\Page($data['title'], "", $data['content'], $pageID);
    }

    public function EditSuccess($pageID){
        if($pageID > 0){
            $this->editForm->InjectFormSuccess('<a class="confirmed-add" href="/admin/page">You edited a page!</a>');
        }else{
            $this->editForm->InjectFormSuccess('<a class="confirmed-add" href="/admin/page">You added a page!</a>');
        }
    }

    public function GoToIndex(){
        header('Location: /admin/page');
        die();
    }

}