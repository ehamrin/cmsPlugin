<?php


namespace Form\model\input\dev;


class Select extends \Form\model\Element
{
    private $options = array();
    public function AddOption(\Form\model\Option ...$options){
        foreach($options as $option){
            $this->options[$option->GetName()] = $option;
        }
    }

    /**
     * @return \Form\model\Option[]
     */
    public function GetOptions(){
        return $this->options;
    }
}