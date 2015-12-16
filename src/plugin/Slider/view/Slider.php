<?php


namespace plugin\Slider\view;


class Slider
{
    public function RenderPageWidget(){
        $slides = '';
        foreach(scandir(APP_ROOT . 'public/uploads/slider/') as $slide){
            if($slide != '.' && $slide != '..'){
                $slides .= '<div class="slide" style="background-image: url(\'/uploads/slider/' . $slide . '\');"></div>' . PHP_EOL;
            }

        }
        return <<<HTML
    <div id="slider">
        <div class="slider">
            {$slides}
        </div>
    </div>
HTML;
    }
}