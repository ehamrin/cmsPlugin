<?php


namespace Form\model\validation;


class IP extends \Form\model\Validation
{
    public function Validate($value) : \bool
    {
        return empty($value) || filter_var($value, FILTER_VALIDATE_URL);
    }
}