<?php


namespace Form\model\input;


class Select extends \Form\model\ElementFacade
{
    public function AddOption(\Form\model\Option ...$options){
        $this->object->AddOption(...$options);
        return $this;
    }
}