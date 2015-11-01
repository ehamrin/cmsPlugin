<?php

class PluginNotValidException extends Exception{}

class PluginFacade
{
    /**
     * @var $reflect ReflectionClass
     */
    private $reflect;
    private $meta;

    /**
     * @var $instance IPlugin
     */
    private $instance;

    /**
     * @param string $className
     * @param string $filePath
     * @throws PluginNotValidException
     */
    public function __construct($className, $filePath){
        if(!is_file($filePath)){
            throw new PluginNotValidException("Could not find the plugin file {$filePath}");
        }

        $this->reflect = new ReflectionClass($className);

        if(!$this->reflect->implementsInterface('\\IPlugin')) {
            throw new PluginNotValidException("The plugin does not implement the IPlugin Interface");
        }

        $this->meta = $this->GenerateMeta($filePath);
    }

    public function AddInstance(\Application $application){
        $this->instance = $this->reflect->newInstance($application);
    }

    public function RemoveInstance(){
        $this->instance = null;
        $this->reflect = null;
    }

    /* @return ReflectionClass */
    public function GetReflection(){
        return $this->reflect;
    }

    public function GetMeta(){
        return $this->meta;
    }

    /* @return IPlugin */
    public function GetInstance(){
        return $this->instance;
    }

    public function GetPluginName(){
        return $this->reflect->getShortName();
    }

    public function Exists(){
        return $this->reflect !== null;
    }

    public function HasInstance(){
        return $this->instance !== null;
    }

    private function GenerateMeta($filePath){
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

        if(file_exists($filePath)){

            foreach(file($filePath) as $line){
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
}