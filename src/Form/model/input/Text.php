<?php


namespace Form\model\input;

class Text extends \Form\model\ElementFacade
{
    public function __construct($name, $value = "", $type="text")
    {
        parent::__construct($name, $value);
        $this->SetType($type);
    }

    public function SetType(\string $type){
        $this->object->SetType($type);
    }
}