<?php


namespace app;

use Windwalker\Renderer\BladeRenderer;

class AbstractController
{
    protected $application;

    public function __construct(\Application $application){
        $this->application = $application;
     }

    protected function Verify($permission){
        return $this->application->IsAuthenticated() && $this->application->GetUser()->Can($permission);
    }

    protected function Authorize($permission){
        $verified = $this->Verify($permission);
        if(!$verified){
            header('HTTP/1.0 403 Forbidden');
        }

        return $verified;
    }

    protected function View($template, $data = array()){
        $paths = array();

        foreach($this->application->GetActivePluginReflections() as $reflection){
            $dir = dirname($reflection->getFileName()) . '/views';
            if(is_dir($dir)){
                $paths[] = dirname($reflection->getFileName()) . '/views';
            }
        }

        $renderer = new BladeRenderer($paths, array('cache_path' => APP_ROOT . 'cache/blade'));

        return $renderer->render($template, $data);
    }

    protected function requestMethod(){
        return $_SERVER['REQUEST_METHOD'];
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

    public function Redirect($location){
        header('Location: ' . $location);
        die();
    }

}