<?php


namespace plugin\Page\view;

use \plugin\Page\model;

class Page
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
        $pageHTML = $page->GetContent();

        $headerHook = '';
        foreach ($this->application->InvokeEvent("PageHeaderHTML", $page) as $event) {
            $headerHook .= $event->GetData();
        }
        return <<<HTML
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>{$page->GetName()}</title>
    <meta name="description" content="{$page->GetName()}">
    <meta name="author" content="Erik Hamrin">

    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/vendors/fancybox/jquery.fancybox.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/styles.css?v=1.0">
    <script src="/scripts/jquery.js"></script>
    <script type="text/javascript" src="/scripts/scripts.js"></script>
    <script type="text/javascript" src="/vendors/fancybox/jquery.fancybox.pack.js"></script>
</head>

<body>
    {$headerHook}
    <nav>
        {$this->RenderNav($page)}
    </nav>
    <header><h1>{$page->GetName()}</h1></header>
    <main>

        <div class="wrapper">
            {$pageHTML}
        </div>
    </main>

</body>
</html>
HTML;

    }

    private function RenderNav(model\Page $current){

        $ret = "";
        foreach($this->model->FetchAll() as $page){
            $currentItem = $page->GetSlug() == $current->GetSlug() ? ' class="current"' : '';
            $ret .= '<li' . $currentItem . '><a href="/' . $page->GetSlug() . '">' . $page->GetName() . '</a></li>';
        }

        $ret = '<ul>' . $ret . '</ul>';

        return $ret;
    }

    public function AdminList(){
        $rows = "";
        foreach($this->model->FetchAll() as $page){
            $rows .= '
            <tr>
                <td>' . $page->GetName() . '</td>
                <td>' . $page->GetSlug() . '</td>
                <td><a href="/' . $page->GetSlug() . '" target="_blank" class="edit"><span class="fa fa-eye"></span></a></td>
                <td><a href="/admin/page/edit/' . $page->GetID() . '" class="edit"><i class="fa fa-pencil-square-o"></i></a></td>
                <td>' . ($page->GetID() > 1 ? '<a href="/admin/page/delete/' . $page->GetID() . '" class="delete"><i class="fa fa-trash"></i></a>' : '') . '</td>
            </tr>' . PHP_EOL;
        }

        return <<<HTML
    <table>
        <tr>
            <th>Page</th>
            <th>Slug</th>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        {$rows}
    </table>
HTML;
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