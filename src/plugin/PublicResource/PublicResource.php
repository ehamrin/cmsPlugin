<?php


namespace plugin\PublicResource;

/**
 * @Name Public resource manager
 * @Description Manages public resources for plugins
 * @Author Erik Hamrin
 * @Version v0.5
 * @Icon fa-users
 */


class PublicResource extends \plugin\AbstractPlugin
{
    public function HookRootAccess($method){
        $method = strtolower($method);
        if($method == 'css' || $method == 'js' || $method == 'json'){
            return true;
        }
        return false;
    }

    public function HookPluginSettings(){
        return array(
            new \plugin\Settings\model\Setting('json-cache-time', 10, 'Cache time for json files (minutes)'),
            new \plugin\Settings\model\Setting('js-cache-time', 1440, 'Cache time for javascript files (minutes)'),
            new \plugin\Settings\model\Setting('css-cache-time', 1440, 'Cache time for css files (minutes)')
        );
    }

}