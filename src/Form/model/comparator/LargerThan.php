<?php


namespace Form\model\comparator;


class LargerThan extends \Form\model\Comparator
{
    public function Validate($self, $other) : \bool
    {
        return ($self <=> $other) == 1;
    }
}