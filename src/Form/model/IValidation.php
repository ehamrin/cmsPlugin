<?php


namespace Form\model;


interface IValidation
{
    public function Validate($value) : \bool ;
    public function GetMessage() : \string ;
}