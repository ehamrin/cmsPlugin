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
    public function __construct($className){
        $this->reflect = new ReflectionClass($className);

        if(!$this->reflect->implementsInterface('\\IPlugin')) {
            throw new PluginNotValidException("The plugin does not implement the IPlugin Interface");
        }

        $this->meta = $this->GenerateMeta();
    }

    public function AddInstance(\Application $application){
        $this->instance = $this->reflect->newInstance($application);

        if($this->instance->IsInstalled() == false){
            $this->instance->Install();
        }
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

    private function GenerateMeta(){
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

        if($comment = $this->reflect->getDocComment()){
            $comment = str_replace(array('/**', '*/'), '', $comment);
            $lines = explode('*', $comment);

            foreach($lines as $line){
                foreach($lookFor as $name){
                    if(strpos('@' . $line, $name) !== false){
                        $line = str_replace('@' . $name, '', $line);
                        $obj->{$name} = trim($line);
                    }
                }
            }
        }

        return $obj;
    }
}