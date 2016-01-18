<?php


namespace plugin\Slider\view;

use \plugin\Slider\model\Slide;

class Slider
{
    public function __construct(\Application $application)
    {
        $this->application = $application;
    }

    public function RenderPageWidget($slideModels){

        $this->application->AddScriptDependency("/js/Slider/Slider.js");
        $this->application->AddCSSDependency("/css/Slider/Slider.css");
        /* @var $slideModels Slide[] */
        $slides = '';
        foreach($slideModels as $slide){
            $slides .= '<div class="slide" style="background-image: url(\'' . $slide->getPublicFilename() . '\');"></div>' . PHP_EOL;
        }
        return <<<HTML
    <div id="slider">
        <div class="slider">
            {$slides}
        </div>
    </div>
HTML;
    }

    public function Index($slides){
        $ret = '<h1>Slider admin panel</h1>
               <a href="/admin/slider/create">Create new slider image</a>
            <table>';
        foreach($slides as $slide){
            /* @var $slide Slide */
            $ret .= '<tr>
                    <td>' . $slide->getName() . '</td>
                    <td>' . $slide->getFilename() . '</td>
                    <td>' . $slide->getCreated() . '</td>
                    <td><a href="/admin/slider/edit/' . $slide->getId() . '"><i class="fa fa-pencil-square-o"></i></a></td>
                    <td><a class="delete" href="/admin/slider/delete/' . $slide->getId() . '"><i class="fa fa-trash-o"></i></a></td>
                </tr>
';
        }
        $ret .= '</table>';
        return $ret;
    }

    public function create(Slide $slide){
        return '<form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <strong>Name</strong>
                        <input type="text" name="name" value="' . $slide->getName() . '"/>
                        ' . $this->getModelError($slide, "name") . '
                    </div>
                    <div class="form-group">
                        <input type="file" name="slide" />
                        ' . $this->getModelError($slide, "filename") . '
                    </div>
                    <div class="form-group">
                        <button type="submit">Ladda upp</button>
                    </div>
                </form>
                ';
    }

    private function getModelError(Slide $slide, $name){
        if(!isset($slide->getModelError()[$name])){
            return "";
        }
        $ret  = '<ul class="error-list">';
        if(is_array($slide->getModelError()[$name])){
            foreach($slide->getModelError()[$name] as $error){
                $ret .= "<li>$error</li>";
            }
        }else{
            $ret .= "<li>" . $slide->getModelError()[$name] . "</li>";
        }

        $ret .= '</ul>';
        return $ret;
    }

    public function hasFile(){
        return isset($_FILES["slide"]) && !empty($_FILES["slide"]["name"]);
    }

    public function isPost(){
        return isset($_POST["name"]);
    }

    public function GoToIndex(){
        header('Location: /admin/slider');
        die();
    }
}