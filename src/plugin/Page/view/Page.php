<?php


namespace plugin\Page\view;

use \plugin\Page\model;

class Page extends \plugin\AbstractView
{
    private $settingModel;
    private $widgets;
    public function __construct(\Application $application, model\PageModel $model){
        $this->application = $application;
        $this->model = $model;
        $this->editForm = $this->CreateEditForm();
        $this->ScanTemplatesForWidgetUse();
    }

    private function CreateEditForm(){
        $form = new \Form\controller\FormController("EditPage");

        $options = array();
        $dir = scandir(__DIR__ . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'templates/');

        //Remove parent dir
        array_shift($dir);
        array_shift($dir);

        $widgets = array();

        foreach($dir as $option){
            $option = str_replace('.php', '', $option);
            $options[] = new \Form\model\Option(ucfirst($option), $option);
        }

        $form->AddInput(
            (new \Form\model\input\Text("title"))
                ->SetLabel("Title"),
            (new \Form\model\input\Textarea("content"))
                ->SetAttributes(new \Form\model\Option("class", "tinyMCE")),
            (new \Form\model\input\Select('template'))
                ->AddOption(...$options)
                ->SetLabel("Template"),
            (new \Form\model\input\Submit("submit", "Submit"))
        );
        return $form;
    }

    private function ScanTemplatesForWidgetUse(){
        $dir = scandir(__DIR__ . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'templates/');

        //Remove parent dir
        array_shift($dir);
        array_shift($dir);

        $widgets = array();

        foreach($dir as $option){
            $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'templates/' . $option);

            preg_match_all('/DoWidget\(.*?\)/', $file, $matches);

            if(count($matches[0])){
                $option = str_replace('.php', '', $option);
                $widgets[$option] = array();

                foreach($matches[0] as $match){
                    $widgetHolder = preg_replace("/DoWidget\(('|\")(.*?)('|\")(.*?)\)/", '$2', $match);
                    $widgets[$option][$widgetHolder] = $widgetHolder;
                }
            }
        }
        $this->widgets = $widgets;
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
        return $this->View('AdminList', array('widgets' => $this->widgets));
    }

    public function Edit($pageID){
        if($pageID > 0){
            $page = $this->model->FindByID($pageID);
            $this->editForm->UpdateValue("title", $page->GetName());
            $this->editForm->UpdateValue("content", $page->GetContent());
            $this->editForm->UpdateValue("template", $page->GetTemplate());
        }
        return $this->editForm->GetView();
    }

    public function UserSubmitted(){
        return $this->editForm->WasSubmitted();
    }

    public function GetUpdatedPage($pageID){
        $data = $this->editForm->GetData();

        $page = new model\Page();
        $page->SetName($data['title']);
        $page->SetContent($data['content']);
        $page->SetID($pageID);
        $page->SetTemplate($data['template']);

        return $page;
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