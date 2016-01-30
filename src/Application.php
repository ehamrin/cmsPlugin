<?php


class Application
{
    private static $pluginNamespace = 'plugin';
    private static $appNamespace = 'app';
    public static $appDirectory = APP_ROOT . 'src/app/';
    public static $pluginDirectory = APP_ROOT . 'src/plugin/';
    private static $widgetDirectory = APP_ROOT . 'src/widget/';

    /* @var $plugins PluginFacade[] */
    private $plugins = array();
    private $widgets = array();

    public  function __construct($url){
        $url = explode('/', $url);
        if(empty($url[count($url)-1])){
            unset($url[count($url)-1]);
        }

        $action = ucwords(array_shift($url));

        $this->BindPlugins();


        $content = false;

        try{
            $this->CheckPluginsToRun();
            $this->LoadWidgets();

            foreach($this->plugins as $name => $plugin) {
                $event = 'HookRootAccess';

                if($plugin->Exists() && $plugin->GetReflection()->hasMethod($event)) {
                    $reflectMethod = $plugin->GetReflection()->getMethod($event);

                    //Create a new instance of the class
                    $instance = $this->GetPlugin($name);


                    if ($reflectMethod->invoke($instance, $action)) {
                        $content = $instance->Init('Public', $action, ...$url);
                    }
                }
            }

        }catch (\PDOException $e){
            $this->InvokeEvent('UncaughtDBError', $e);
        }catch (\Exception $e){
            $this->InvokeEvent('UncaughtError', $e);
        }

        if($content != false){
            echo $content;
        } else {
            header("HTTP/1.0 404 Not Found");
            echo file_get_contents(APP_ROOT . 'error.html');
        }


    }

    private function BindPlugins(){
        $appPlugins = scandir(self::$appDirectory);

        //Remove parent directory "." and ".."
        array_shift($appPlugins);
        array_shift($appPlugins);

        $userPlugins = scandir(self::$pluginDirectory);

        //Remove parent directory "." and ".."
        array_shift($userPlugins);
        array_shift($userPlugins);

        $plugins = array_merge($appPlugins, $userPlugins);

        foreach($plugins as $plugin) {
            try{
                if($plugin != "__default") {
                    $pluginFacade = $this->CreatePluginFacade($plugin);
                    $this->plugins[$plugin] = $pluginFacade;
                }
            }catch(\Exception $e){
                //It's not a valid plugin
                // TODO Show exception message
            }
        }


    }

    public function CheckPluginsToRun(){
        //Check if a plugin wants to control what other plugins are activated

        $activePlugins = $this->InvokeEvent('ActivatedPlugins', $this->plugins);

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

    public function LoadWidgets(){
        $widgets = array();
        foreach($this->plugins as $pluginName => $plugin){
            $dir = self::$pluginDirectory . $pluginName . DIRECTORY_SEPARATOR . 'widget';

            if(is_dir($dir)){
                $pluginWidgets = scandir($dir);
                //Remove parent directory "." and ".."
                array_shift($pluginWidgets);
                array_shift($pluginWidgets);
                foreach($pluginWidgets as $widget){
                    if(is_file($dir . DIRECTORY_SEPARATOR . $widget . DIRECTORY_SEPARATOR . $widget . '.php')) {
                        $widgets[$widget] = '\\' . self::$pluginNamespace . '\\' . $pluginName . '\\widget\\' . $widget . '\\' . $widget;
                    }
                }
            }
        }

        if(is_dir(self::$widgetDirectory)){
            $externalWidgets = scandir(self::$widgetDirectory);
            //Remove parent directory "." and ".."
            array_shift($externalWidgets);
            array_shift($externalWidgets);
            foreach($externalWidgets as $widget){
                if(is_file(self::$widgetDirectory . $widget . DIRECTORY_SEPARATOR . $widget . '.php')){
                    $widgets[$widget] = '\\widget\\' . $widget . '\\' . $widget;
                }
            }
        }

        foreach($widgets as $widget => $className){
            $reflection = new ReflectionClass($className);

            if($reflection->implementsInterface('\\IWidget')) {
                $this->widgets[$widget] = $reflection->newInstance();
            }
        }
    }

    public function GetConstantPlugins(){
        return array(
            'Admin',
            'Authentication',
            'PluginHandler',
            'Settings',
            'Logger',
            'Sitemap',
            'Page',
            'PublicResource'
        );
    }

    public function Remove($plugin){
        $this->plugins[$plugin]->RemoveInstance();
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

                $return = array_merge($return, array($name => new Event($instance, $list)));
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
            return \app\Authentication\model\UserModel::IsAuthenticated();
        }

        return true;
    }

    public function GetUser()
    {
        if($this->PluginExists('Authentication') && $this->IsAuthenticated()){
            return \app\Authentication\model\UserModel::GetLoggedInUser();
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

    /**
     * @return \ReflectionClass[]
     */
    public function GetActivePluginReflections()
    {
        $ret = array();
        foreach ($this->plugins as $plugin) {
            if($plugin->Exists()){
                $ret[$plugin->GetPluginName()] = $plugin->GetReflection();
            }
        }
        return $ret;
    }

    public function GetWidget(\string $widget = null)
    {
        if($widget == null){
            return $this->widgets;
        }

        if(!$this->widgets[$widget]){
            return false;
        }

        return $this->widgets[$widget];
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

    public function Settings(){

        $model = new \app\Settings\model\SettingModel();
        return $model->GetAll();
    }

    public function Setting($name){

        $model = new \app\Settings\model\SettingModel();
        return $model->Get($name);
    }

    public function CreatePluginFacade($plugin){
        if(is_file(self::$pluginDirectory . $plugin . DIRECTORY_SEPARATOR . $plugin . '.php')){
            $pluginClassName = '\\' . self::$pluginNamespace . '\\' . $plugin . '\\' . $plugin;
            return new PluginFacade($pluginClassName);
        }elseif(is_file(self::$appDirectory . $plugin . DIRECTORY_SEPARATOR . $plugin . '.php')){
            $pluginClassName = '\\' . self::$appNamespace . '\\' . $plugin . '\\' . $plugin;
            return new PluginFacade($pluginClassName);
        }
        throw new Exception("The plugin file does not exist!");
    }

    private $scripts = array();

    public function AddScriptDependency($script){
        if(!in_array($script, $this->scripts)) {
            $this->scripts[] = $script;
        }
    }

    public function GetScriptDependency(){
        return $this->getDynamicFile($this->scripts, 'scripts', 'js');
    }

    private $stylesheets = array();

    public function AddCSSDependency($stylesheet){
        if(!in_array($stylesheet, $this->stylesheets)){
            $this->stylesheets[] = $stylesheet;
        }

    }

    public function GetCSSDependency(){
        return $this->getDynamicFile($this->stylesheets, 'css', 'css');
    }

    private function getDynamicFile(array $collection, $folder, $extension)
    {
        if(DEBUG){
            return $collection;
        }

        $this->checkCacheDirectory($folder);

        $return = array();
        $content = "";
        $directory = "";

        foreach ($collection as $file) {
            $file = preg_replace('/\?.*/', '', $file);
            if(is_file(APP_ROOT . "public/$file")){

                $content .= file_get_contents(APP_ROOT . "public/$file");
            }else{
                $args = explode('/', trim($file, '/'));

                switch($args[0]){
                    case "js":
                        $directory = "scripts";
                        break;
                    case "css":
                        $directory = "css";
                        break;
                }

                if(is_file(self::$appDirectory . "$args[1]/public/$directory/$args[2]")){
                    $content .= file_get_contents(self::$appDirectory . "$args[1]/public/$directory/$args[2]");
                }else if(is_file(self::$pluginDirectory . "$args[1]/public/$directory/$args[2]")){
                    $content .= file_get_contents(self::$pluginDirectory . "$args[1]/public/$directory/$args[2]");
                }else{
                    $return[] = $file;
                }
            }
        }

        if($content != ""){
            $name = strtolower(preg_replace('/[0-9_\/=]+/','',base64_encode(sha1($content)))) . ".$extension";
            $publicPath = "/cache/$folder/";
            $filePath = APP_ROOT . 'public' . $publicPath;

            if(!is_file($filePath . $name)) {
                $handle = fopen($filePath . $name, 'w');
                fwrite($handle, $content);
                chmod($filePath . $name, 0775);
            }

            $return[] = $publicPath . $name;

        }
        return $return;
    }

    private function checkCacheDirectory($folder)
    {
        if(!is_dir(APP_ROOT . 'public/cache/')){
            mkdir(APP_ROOT . 'public/cache/');
        }

        if(!is_dir(APP_ROOT . "public/cache/$folder/")){
            mkdir(APP_ROOT . "public/cache/$folder/");
        }
    }
}