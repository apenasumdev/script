<?php

/**
 * Middleware
 * @var Lite\Routing\Router $router
 */
$router->before('GET|POST', '/api/.*', function() {
	if(!isset($_SERVER['HTTP_TOKEN'])
		|| $_SERVER['HTTP_TOKEN'] !== config('app.token')){
		view('404',['error'=>'Permission Denied ❌']);
		die();
	}
});