<?php


namespace Form\model\validation;


class MinLength extends \Form\model\Validation
{
    private $min;

    public function __construct(\int $min, \string $message)
    {
        parent::__construct($message);
        $this->min = $min;

    }

    public function Validate($value) : \bool
    {
        return empty($value) || (is_string($value) && strlen($value) >= $this->min);
    }
}