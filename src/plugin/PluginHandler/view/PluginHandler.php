<?php


namespace plugin\PluginHandler\view;

use \plugin\PluginHandler\model;

class PluginHandler
{
    private $model;
    public function __construct(model\PluginHandlerModel $model){
        $this->model = $model;
    }
    public function AdminList(\Application $application){
        $ret = '<form action="" method="POST"><table><tr><th>Plugin Name</th><th>Active</th><th>Description</th><th>Version</th></tr>';

        $installed = $this->model->InstalledPlugins();
        foreach($this->model->GetAvailablePlugins() as $available){
            if(!in_array($available, $application->GetConstantPlugins())){

                $data = $application->GetPluginMeta($available);
                $ret .= '<tr><td>' . (!empty($data->Name) ? $data->Name : $available) . '</td><td>
                <div class="onoffswitch">
                    <input type="hidden" name="plugin[' . $available . '][action]" value=""/>
                    <input type="checkbox" name="plugin[' . $available . '][value]" id="myonoffswitch_' . $available . '" class="onoffswitch-checkbox checkbox-submit" ' . (in_array($available, $installed) ? 'checked="checked"' : '') . '/>
                    <label class="onoffswitch-label" for="myonoffswitch_' . $available . '">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
                </td>
                <td>' . (!empty($data->Description) ? $data->Description : '<em>Unavailable</em>') . '</td>
                <td>' . (!empty($data->Version) ? $data->Version : '<em>Unavailable</em>') . '</td>';
            }
        }

        return $ret . '</table><input type="hidden" name="placeholder" value="1"/><button type="submit" name="submit">Submit</button></form>';
    }

    public function WasSubmitted(){
        return isset($_POST['submit']);
    }

    public function GetData(){
        $ret = array();
        foreach($_POST['plugin'] as $key => $plugin){
            if(isset($plugin['value'])){
                $ret[$key] = 'Install';
            }else{
                $ret[$key] = $plugin['action'];
            }

        }
        return $ret;
    }

    public function Success(){
        header('Location: ' . $_SERVER['REQUEST_URI']);
    }

}