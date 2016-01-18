<?php


namespace app\PublicResource;

/**
 * @Name Public resource manager
 * @Description Manages public resources for plugins
 * @Author Erik Hamrin
 * @Version v0.5
 * @Icon fa-users
 */


class PublicResource extends \app\AbstractPlugin
{
    public function HookRootAccess($method){
        $method = strtolower($method);
        if($method == 'css' || $method == 'js' || $method == 'json' || $method == 'image'){
            return true;
        }
        return false;
    }

    public function HookPluginSettings(){
        return array(
            new \app\Settings\model\Setting('json-cache-time', 10, 'Cache time for json files (minutes)'),
            new \app\Settings\model\Setting('js-cache-time', 1440, 'Cache time for javascript files (minutes)'),
            new \app\Settings\model\Setting('css-cache-time', 1440, 'Cache time for css files (minutes)'),
            new \app\Settings\model\Setting('image-cache-time', 1440, 'Cache time for image files (minutes)')
        );
    }

}