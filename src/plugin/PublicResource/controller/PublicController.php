<?php


namespace plugin\PublicResource\controller;
use plugin\PublicResource\model;
use plugin\PublicResource\view;

class PublicController
{
    private static $cache_life = 5; //Minutes

    public function __construct(\Application $application){
        $this->application = $application;
    }

    public function Css( ...$args){
        return $this->GetFile("text/css", 1440, "public/css", ...$args);
    }

    public function Js( ...$args){
        return $this->GetFile("text/javascript", 1440, "public/scripts",  ...$args);
    }

    public function Json( ...$args){
        return $this->GetFile("application/json", 10, "public/json", ...$args);
    }

    private function GetFile($contentType, $cacheLife, $directory, $plugin, $filename){
        $cacheDir = \Application::$pluginDirectory;
        $file = $cacheDir. "$plugin/$directory/$filename";

        if(is_file($file)){

            $last_modified_time = filemtime($file);
            $etag = md5_file($file);

            header('Last-modified: '.gmdate('D, d M Y H:i:s',$_SERVER['REQUEST_TIME']).' GMT');
            header("Etag: $etag");

            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time ||
                @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }else{
                header("Content-Type: $contentType");
                header('Cache-Control: public, max-age=' . $cacheLife * 60);
                header('Expires: ' . gmdate('D, d M Y H:i:s', time()+ ($cacheLife * 60)) . ' GMT');
                header_remove('Pragma');
            }

            return file_get_contents($file);

        }elseif(method_exists($this->application->GetPlugin($plugin), 'HookJSON')){
            $file = $this->application->GetPlugin($plugin)->HookJSON($filename);

            if($file != null && $file != false){
                header("Content-Type: $contentType");
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
                return $file;
            }
        }

        return false;

    }
}