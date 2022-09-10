<?php

/**
 * API ROUTES
 * @var Lite\Routing\Router $router
 */
//Auth Routes
$router->post('login','Auth\AuthController@login');
$router->post('logout','Auth\AuthController@logout');
$router->post('user/edit','Auth\AuthController@edit');

//API
$router->post('upload/image','API\ImageController@upload');
$router->post('page/{action}','API\PageController@index');
$router->mount('/v1',function() use($router){
	$router->get('fetch','API\v1\FetchController@index');
	$router->get('fetch-by-json', 'API\v1\FetchController@fetchByJson');
	$router->post('/fetch-videos/{video_id}','API\v1\FetchController@fetch');
});
$router->get('update/{action}','API\SettingController@index');
$router->post('update/{action}','API\SettingController@index');



//RECENT AND TRENDING
$router->get('recent','HomeController@getRecentVideos');
$router->get('trending','HomeController@getTrendingVideos');

/**
 * Proxy Routes
 * @since 2.2.9
 */
$router->get('proxies','API\ProxyController@get');
$router->post('proxies', 'API\ProxyController@add');
$router->delete('proxies','API\ProxyController@delete');