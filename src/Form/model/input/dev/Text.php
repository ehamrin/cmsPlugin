<?php


namespace Form\model\input\dev;

class InputTypeNotSupportedException extends \Exception{}

class Text extends \Form\model\Element
{
    private $type;

    public function __construct($name, $value = "", $type="text")
    {
        parent::__construct($name, $value);

        $this->SetType($type);
    }

    public function SetType(\string $type){
        switch($type) {
            case 'text':
            case 'date':
            case 'time':
            case 'number':
            case 'email':
            case 'hidden':
            case 'month':
            case 'search':
            case 'tel':
            case 'url':
                $this->type = $type;
                break;
            default:
                throw new InputTypeNotSupportedException();
        }
    }

    public function GetType() : \string
    {
        return $this->type;
    }
}