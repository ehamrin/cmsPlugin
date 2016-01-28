<?php


namespace app;


abstract class AbstractPlugin implements \IPlugin
{
    protected $application;

    public function __construct(\Application $application){
        $this->application = $application;
    }

    public function Init($controller, $method="Index", ...$params){
        $controllerName = ucfirst($controller) . "Controller";
        if(is_file("{$this->getDir()}/controller/{$controllerName}.php")){
            $className = $this->getNamespace() . "\\controller\\" . $controllerName;
            $controllerObj = $this->{$controllerName} ?? new $className($this->application);

            $method = str_replace(['_','-'], '', $method);
            if(method_exists($controllerObj, $method)){
                return $controllerObj->{$method}(...$params);
            }
        }

        return false;
    }

    public function Install(){}

    public function Uninstall(){}

    public function IsInstalled(){
        return true;
    }

    private function getDir() {
        $rc = new \ReflectionClass(get_class($this));
        return dirname($rc->getFileName());
    }

    private function getNamespace() {
        $rc = get_class($this);
        $arr = explode("\\", $rc);
        array_pop($arr);

        return implode('\\', $arr);
    }

    protected function TableExists($table){
        try {
            $result = \Database::GetConnection()->query("SELECT 1 FROM $table LIMIT 1");
        } catch (\Exception $e) {
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }

    protected function RemoveTable($table){
        \Database::GetConnection()->exec("
          DROP TABLE IF EXISTS `$table`
        ");
    }

}