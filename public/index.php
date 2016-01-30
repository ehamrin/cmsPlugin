<?php

define('APP_ROOT', dirname(getcwd()) . DIRECTORY_SEPARATOR);

define('DEBUG', TRUE);
define('HTTPS', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'));

ini_set('display_errors', DEBUG);
error_reporting(E_ALL);

require_once APP_ROOT . 'includes/functions.php';

if(is_file(APP_ROOT . 'vendor/autoload.php')){
    require APP_ROOT . 'vendor/autoload.php';
}

$currentCookieParams = session_get_cookie_params();

$lifetime=0;
$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';

session_set_cookie_params(
    $lifetime,
    $currentCookieParams["path"],
    $_SERVER['HTTP_HOST'],
    HTTPS,
    true
);
session_start();


$parameters = isset($_GET['url']) ? $_GET['url'] : '';

if(!class_exists("Settings")){
    require_once APP_ROOT . 'includes/setup.php';
}else{
    new \Application($parameters);
}