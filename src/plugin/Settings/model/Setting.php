<?php


namespace plugin\Settings\model;


class Setting
{
    private $name;
    private $value;
    private $description;

    public function __construct($name, $value, $description = ""){
        $this->name = $name;
        $this->value = $value;
        $this->description = $description;
    }

    public function GetName(){
        return $this->name;
    }

    public function GetValue(){
        return $this->value;
    }

    public function GetDescription(){
        return $this->description;
    }

}