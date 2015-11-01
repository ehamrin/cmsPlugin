<?php


namespace Form\model\validation;


class Required extends \Form\model\Validation
{
    public function Validate($value) : \bool
    {
        return !(is_null($value) || empty(trim($value)));
    }
}