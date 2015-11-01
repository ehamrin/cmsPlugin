<?php


class Event
{
    private $plugin;
    private $args;

    public function __construct(IPlugin $plugin, $args){
        $this->plugin = $plugin;
        $this->args = $args;
    }

    public function GetHookListener() : IPlugin
    {
        return $this->plugin;
    }

    public function GetData(){
        return $this->args;
    }
}