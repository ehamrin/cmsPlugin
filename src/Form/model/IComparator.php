<?php


namespace Form\model;


interface IComparator
{
    public function Validate($self, $other) : \bool;
    public function GetMessage() : \string ;
    public function GetName() : \string ;
}