<?php

define('APP_ROOT', dirname(getcwd()) . DIRECTORY_SEPARATOR);

define('DEBUG', TRUE);

ini_set('display_errors', DEBUG);
error_reporting(E_ALL);

require_once APP_ROOT . 'includes/functions.php';
session_start();

$parameters = isset($_GET['url']) ? $_GET['url'] : '';

if(!class_exists("Settings")){
    require_once APP_ROOT . 'includes/setup.php';
}else{
    new \Application($parameters);
}