<?php


namespace Form\model;


abstract class Comparator implements IComparator
{
    private $message;
    private $input;

    public function __construct(\string $inputName, \string $message)
    {
        $this->message = $message;
        $this->input = $inputName;
    }

    public function GetMessage() : \string
    {
        return $this->message;
    }

    public function GetName() : \string
    {
        return $this->input;
    }
}