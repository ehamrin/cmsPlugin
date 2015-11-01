<?php


namespace Form\model\validation;


class Numeric extends \Form\model\Validation
{
    public function Validate($value) : \bool
    {
        return empty($value) || is_numeric($value);
    }
}
