<?php


namespace Form\model\validation;


class LessThanEqual extends \Form\model\Validation
{
    private $min;

    public function __construct(\int $min, \string $message)
    {
        parent::__construct($message);
        $this->min = $min;
    }

    public function Validate($value) : \bool
    {
        if(!is_numeric($value)){
            return false;
        }

        return empty($value) || ($value <= $this->min);
    }
}