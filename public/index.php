<?php

define('APP_ROOT', dirname(getcwd()) . DIRECTORY_SEPARATOR);
define('DEBUG', TRUE);
require_once APP_ROOT . 'includes/functions.php';
session_start();

if(!class_exists("Settings")){
    require_once APP_ROOT . 'includes/setup.php';
}else{
    new \Application($_GET['url']);
}



