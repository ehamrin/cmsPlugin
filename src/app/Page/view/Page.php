<?php


namespace app\Page\view;

use \app\Page\model;

class Page extends \app\AbstractView
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
        $moduleOptions = array();
        $dir = scandir(__DIR__ . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'templates/');

        //Remove parent dir
        array_shift($dir);
        array_shift($dir);

        $widgets = array();

        foreach($dir as $option){
            $option = str_replace('.php', '', $option);
            $options[] = new \Form\model\Option(ucfirst($option), $option);
        }

        $moduleOptions[] = new \Form\model\Option('--Välj modul--', '');

        foreach($this->application->InvokeEvent("PageModules") as $event){
            foreach($event->GetData() as $option) {

                $moduleOptions[] = new \Form\model\Option(ucfirst($option), $option);
            }

        }

        $form->AddInput(
            (new \Form\model\input\Text("title"))
                ->SetLabel("Title"),
            (new \Form\model\input\Textarea("content"))
                ->SetAttributes(new \Form\model\Option("class", "tinyMCE")),
            (new \Form\model\input\Select('template'))
                ->AddOption(...$options)
                ->SetLabel("Template"),
            (new \Form\model\input\Select('module'))
                ->AddOption(...$moduleOptions)
                ->SetLabel("Module"),
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

            //preg_match_all('/DoWidget\(.*?\)/', $file, $matches);
            preg_match_all("/DoWidget\((\'|\")(.*?)(\'|\")(.*?)\)/", $file, $matches);

            if(count($matches[2])){
                $option = str_replace('.php', '', $option);
                $widgets[$option] = array();

                foreach($matches[2] as $match){
                    $widgets[$option][$match] = $match;
                }
            }
        }
        $this->widgets = $widgets;
    }

    public function RenderCMS(model\Page $page, ...$params){
        $this->application->AddCSSDependency('/css/normalize.min.css');
        $this->application->AddCSSDependency('/vendors/fancybox/jquery.fancybox.css');
        $this->application->AddCSSDependency('/vendors/sweetalert.css');
        $this->application->AddCSSDependency('/css/font-awesome.min.css');
        $this->application->AddCSSDependency('/css/styles.min.css?v=1.1');

        $this->application->AddScriptDependency("/scripts/jquery.js");
        $this->application->AddScriptDependency("/vendors/fancybox/jquery.fancybox.pack.js");
        $this->application->AddScriptDependency("/vendors/sweetalert.min.js");
        $this->application->AddScriptDependency("/scripts/scripts.min.js");

        $headerHook = '';
        foreach ($this->application->InvokeEvent("PageHeaderHTML", $page) as $event) {
            $headerHook .= $event->GetData();
        }

        $moduleHook = '';
        foreach ($this->application->InvokeEvent("PageModule" . $page->getModule(), $params) as $event) {
            $moduleHook .= $event->GetData();
        }


        return $this->View('CMSPage', array('page' => $page, 'headerHook' => $headerHook, 'moduleHook' => $moduleHook, 'settings' => $this->application->Settings()));

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
        $html = '<h1>';
        $title = 'Create new page';

        if($pageID > 0){

            $page = $this->model->FindByID($pageID);
            $title = 'Editing ' . $page->GetName();

            $this->editForm->UpdateValue("title", $page->GetName());
            $this->editForm->UpdateValue("content", $page->GetContent());
            $this->editForm->UpdateValue("template", $page->GetTemplate());
            $this->editForm->UpdateValue("module", $page->GetModule());
        }
        $html .= $title . '</h1>' . $this->editForm->GetView();
        return $html;
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
        $page->SetModule($data['module']);

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