<?php


interface IPlugin {
    public function __construct(\Application $application);
    public function Init($controller, $method="Index", ...$params);
    public function Install();
    public function Uninstall();
    public function IsInstalled();
}