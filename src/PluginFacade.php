<?php


class PluginFacade
{
    private $reflect;
    private $meta;
    private $instance;

    public function __construct(ReflectionClass $reflect, $meta){
        $this->reflect = $reflect;
        $this->meta = $meta;
    }

    public function AddInstance($instance){
        $this->instance = $instance;
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
}