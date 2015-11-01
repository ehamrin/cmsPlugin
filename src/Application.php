<?php


class Application
{
    private static $pluginNamespace = 'plugin';
    private static $pluginDirectory = APP_ROOT . 'src/plugin/';

    /* @var $plugins PluginFacade[] */
    private $plugins = array();

    public  function __construct($url){
        $url = explode('/', $url);
        if(empty($url[count($url)-1])){
            unset($url[count($url)-1]);
        }

        $action = ucwords(array_shift($url));

        $this->BindPlugins();
        $content = false;

        foreach($this->plugins as $name => $plugin) {
            $event = 'HookRootAccess';

            if($plugin->Exists() && $plugin->GetReflection()->hasMethod($event)) {
                $reflectMethod = $plugin->GetReflection()->getMethod($event);

                //Create a new instance of the class
                $instance = $this->GetPlugin($name);
                if ($reflectMethod->invoke($instance, $action)) {
                    $content = $instance->Init($action, ...$url);
                }
            }
        }

        if($content != false){
            echo $content;
        } else {
            header("HTTP/1.0 404 Not Found");
            echo file_get_contents(APP_ROOT . 'error.html');
        }

    }

    private function BindPlugins(){
        $plugins = scandir(self::$pluginDirectory);

        //Remove parent directory "." and ".."
        array_shift($plugins);
        array_shift($plugins);

        foreach($plugins as $plugin) {

            try{
                $this->plugins[$plugin] = $this->CreatePluginFacade($plugin);

            }catch(PluginNotValidException $e){
                //It's not a valid plugin
                // TODO Show exception message
            }
        }

        //Check if a plugin wants to control what other plugins are activated

        $activePlugins = $this->InvokeEvent('ActivatedPlugins', $plugins);

        if(count($activePlugins) === 1){
            foreach($this->plugins as $name => $plugin) {
                foreach($activePlugins as $event) {
                    /* @var $event Event */
                    if (!in_array($name, $event->GetData()) && !in_array($name, $this->GetConstantPlugins())) {
                        $this->plugins[$name]->RemoveInstance();
                    }
                }
            }
        }
    }

    public function GetConstantPlugins(){
        return array('Admin', 'Authentication', 'PluginHandler', 'Settings');
    }

    /**
     * @return \Event[]
     */
    public function InvokeEvent($method, ...$args) {
        $return = array();

        foreach($this->plugins as $name => $plugin) {
            if($plugin->GetReflection() != null && $plugin->GetReflection()->hasMethod('Hook' . $method)) {
                $reflectMethod = $plugin->GetReflection()->getMethod('Hook' . $method);

                //Create a new instance of the class
                /* @var $instance IPlugin */
                $instance = $this->GetPlugin($name);

                $list = $reflectMethod->invoke($instance,...$args);

                $return = array_merge($return, array(new Event($instance, $list)));
            }
        }
        return $return;
    }

    public function PluginExists(\string $plugin) : \bool
    {
        return $this->plugins[$plugin]->GetReflection() != null;
    }

    public function IsAuthenticated() : \bool
    {
        if($this->PluginExists('Authentication')){
            return \plugin\Authentication\model\UserModel::IsAuthenticated();
        }

        return true;
    }

    public function GetUser()
    {
        if($this->PluginExists('Authentication') && $this->IsAuthenticated()){
            return \plugin\Authentication\model\UserModel::GetLoggedInUser();
        }
        return false;
    }

    public function GetPlugin(\string $plugin) : IPlugin
    {
        if(!$this->plugins[$plugin]->HasInstance() && $this->plugins[$plugin]->Exists()){
            $this->plugins[$plugin]->AddInstance($this);
        }

        if(!$this->plugins[$plugin]->HasInstance()){
            throw new \Exception('Plugin ' . $plugin . ' doesn\'t exist');
        }

        return $this->plugins[$plugin]->GetInstance();
    }

    public function GetPluginMeta(\string $plugin)
    {
        return $this->plugins[$plugin]->GetMeta();
    }

    public function InstallPlugin($plugin){
        if(!isset($this->plugins[$plugin]) || !$this->plugins[$plugin]->Exists()){
            $this->plugins[$plugin] = $this->CreatePluginFacade($plugin);
        }

        $plugin = $this->GetPlugin($plugin);

        if(!$plugin->IsInstalled()){
            $plugin->Install();
        }
    }

    public function CreatePluginFacade($plugin){
        $pluginFile =  self::$pluginDirectory . $plugin . DIRECTORY_SEPARATOR . $plugin . '.php';
        $pluginClassName = '\\' . self::$pluginNamespace . '\\' . $plugin . '\\' . $plugin;
        return new PluginFacade($pluginClassName, $pluginFile);
    }
}