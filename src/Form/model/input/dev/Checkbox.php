<?php


namespace Form\model\input\dev;


class Checkbox extends \Form\model\Element
{
    public function Export(){
        if(\Form\Settings::$PopulateCheckboxIndex == true){
            $this->value = (bool)$this->GetValue();
        }

        if(!empty($this->GetValue()) || $this->GetValue() === FALSE){
            return parent::Export();
        }

        return null;
    }
}