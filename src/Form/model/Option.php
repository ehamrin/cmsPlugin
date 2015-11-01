<?php


namespace Form\model;


class Option
{
    private $name;
    private $value;

    public function __construct($name, $value){
        $this->name = $name;
        $this->value = $value;
    }

    public function GetName()
    {
        return $this->name;
    }

    public function GetValue()
    {
        return $this->value;
    }

}