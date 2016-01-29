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

        $this->application->AddScriptDependency("/js/Slider/slide.js");
        $this->application->AddCSSDependency("/css/Slider/Slider.css");
        /* @var $slideModels Slide[] */
        $slides = '';
        foreach($slideModels as $slide){
            $slides .= '<div class="slide" style="background-image: url(\'' . $slide->getPublicFilename() . '\');background-position: center ' . $slide->getAlignment() . ';"></div>' . PHP_EOL;
        }
        return <<<HTML
    <div id="slider">
        <div class="slider">
            {$slides}
        </div>
    </div>
HTML;
    }


    public function hasFile(){
        if(isset($_FILES) && is_array($_FILES)){
            foreach ($_FILES as $name => $item) {
                if(!empty($item["name"])){
                    return true;
                }
            }
        }
        return false;
    }

    public function isPost(){
        return isset($_POST["name"]);
    }

    public function GoToIndex(){
        header('Location: /admin/slider');
        die();
    }
}