<?php


namespace plugin\Slider\view;


class Slider
{
    public function RenderPageWidget(){
        $slides = '';
        foreach(scandir(APP_ROOT . 'public/images/slider/') as $slide){
            if($slide != '.' && $slide != '..'){
                $slides .= '<div class="slide" style="background-image: url(\'/images/slider/' . $slide . '\');"></div>' . PHP_EOL;
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