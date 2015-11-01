<?php


namespace Form\model\comparator;


class EqualTo extends \Form\model\Comparator
{
    public function Validate($self, $other) : \bool
    {
        return ($self <=> $other) == 0;
    }
}