<?php


namespace app;


abstract class AbstractView
{
    private function getDir() {
        $rc = new \ReflectionClass(get_class($this));
        return dirname($rc->getFileName());
    }
    protected function View($name = null, $variables = array()){
        if($name == null){
            return '';
        }

        extract($variables);

        ob_start();
        include $this->getDir() . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $name . '.php';

        return ob_get_clean();
    }
}