<?php


namespace Form\model\input\dev;

class Submit extends \Form\model\Element
{
    public function Export(){
        if(\Form\Settings::$PopulateSubmitIndex == TRUE){
            return $this->GetValue();
        }

        return null;
    }
}