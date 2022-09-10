<?php

define('$INSTALLED$',true);

//CONSTANTS
define('ROOT_PATH',__DIR__);
define('APP_PATH',__DIR__ . DIRECTORY_SEPARATOR . 'lite');
define('STORAGE_PATH',__DIR__ . DIRECTORY_SEPARATOR . 'storage');
define('STORAGE_URL','/storage');

$ssl = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off";
define("SSL_ENABLED", $ssl);


//Load App
require_once(APP_PATH . 
             DIRECTORY_SEPARATOR . 
             'vendor' . 
             DIRECTORY_SEPARATOR . 
             'autoload.php');

//Check if script is installed
if(!defined('INSTALLED')){
	header("location:".base_url().'/install');
	die();
}

//Start Router
\Lite\Routing\RouterFactory::response();
