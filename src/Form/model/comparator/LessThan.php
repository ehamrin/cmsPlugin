<?php


namespace Form\model\comparator;


class LessThan extends \Form\model\Comparator
{
    public function Validate($self, $other) : \bool
    {
        return ($self <=> $other) == -1;
    }
}