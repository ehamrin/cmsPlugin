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

        foreach($this->plugins as $plugin) {
            $event = 'HookRootAccess';

            if($plugin->GetReflection() != null && $plugin->GetReflection()->hasMethod($event)) {
                $reflectMethod = $plugin->GetReflection()->getMethod($event);

                //Create a new instance of the class
                $instance = $this->GetPlugin($plugin->GetReflection()->getShortName());
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

            $pluginFile =  $this->GetPluginFilePath($plugin);
            $pluginClassName = '\\' . self::$pluginNamespace . '\\' . $plugin . '\\' . $plugin;

            if(is_file($pluginFile)){
                $reflectClass = new ReflectionClass($pluginClassName);
                if($reflectClass->implementsInterface('\\IPlugin')) {
                    $this->plugins[$reflectClass->getShortName()] = new PluginFacade($reflectClass, $this->GeneratePluginMeta($reflectClass->getShortName()));
                }
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
        return array('Admin', 'Authentication', 'PluginHandler');
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
                $instance = $this->GetPlugin($plugin->GetReflection()->getShortName());

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
            /* @var $authenticationPlugin \plugin\Authentication\Authentication*/
            $authenticationPlugin = $this->GetPlugin('Authentication');
            return $authenticationPlugin->IsLoggedIn();
        }

        return true;
    }

    public function GetUser()
    {
        if($this->PluginExists('Authentication') && $this->IsAuthenticated()){
            $userModel = $this->plugins['Authentication']->GetReflection()->getProperty('model');
            $userModel->setAccessible(true);
            $userModel = $userModel->getValue($this->GetPlugin('Authentication'));
            /* @var $userModel \plugin\Authentication\model\UserModel */
            return $userModel->GetLoggedInUser();
        }

        return false;
    }

    public function GetPlugin(\string $plugin) : IPlugin
    {
        if($this->plugins[$plugin]->GetInstance() == null && $this->plugins[$plugin]->GetReflection() != null){

            $this->plugins[$plugin]->AddInstance($this->plugins[$plugin]->GetReflection()->newInstance($this));
        }

        if($this->plugins[$plugin]->GetInstance() == null){
            throw new \Exception('Plugin ' . $plugin . ' doesn\'t exist');
        }
        return $this->plugins[$plugin]->GetInstance();
    }

    private function GeneratePluginMeta(\string $plugin)
    {
        $pluginFile = $this->GetPluginFilePath($plugin);
        $inComment = false;
        $obj = new stdClass();

        $lookFor = array(
            'Name',
            'Description',
            'Author',
            'Version',
            'Icon'
        );

        foreach($lookFor as $name){
            $obj->$name = '';
        }

        if(file_exists($pluginFile)){

            foreach(file($pluginFile) as $line){
                if(strpos($line, '/*') !== false){
                    $inComment = true;
                }
                if($inComment){
                    //Remove asterisks
                    $line = str_replace(array('/* ', '* '), '', $line);

                    foreach($lookFor as $name){
                        if(strpos($line, $name . ':') !== false){
                            $line = str_replace($name . ':', '', $line);
                            $obj->{$name} = trim($line);
                        }
                    }

                    if(strpos($line, '*/') !== false){
                        $inComment = false;
                    }
                }

            }
        }
        return $obj;
    }

    public function GetPluginMeta(\string $plugin)
    {
        return $this->plugins[$plugin]->GetMeta();

    }

    private function GetPluginFilePath($plugin){
        return self::$pluginDirectory . $plugin . DIRECTORY_SEPARATOR . $plugin . '.php';
    }
}