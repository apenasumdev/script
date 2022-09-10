<?php
//CONSTANTS
define('ROOT_PATH',__DIR__);
define('APP_PATH',__DIR__ . DIRECTORY_SEPARATOR . 'installer');
define('VIEW_PATH', APP_PATH . DIRECTORY_SEPARATOR . 'views');
define('MAIN_ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..');
define('MAIN_APP_PATH', MAIN_ROOT_PATH . DIRECTORY_SEPARATOR . 'lite');


$ssl = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off";
define("SSL_ENABLED", $ssl);

//Load App
require_once(APP_PATH .
	DIRECTORY_SEPARATOR .
	'vendor' .
	DIRECTORY_SEPARATOR .
	'autoload.php');

//Run Router
Lite\Routing\RouterFactory::response();