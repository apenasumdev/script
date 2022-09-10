<?php


/**
 * WEB ROUTES
 * @var Lite\Routing\Router $router
 */

$router->set404(function(){
    view('404',['error'=>'404','msg'=>'Page not found! 🚫']);
});

//Download Route
$router->get('/download','DownloadController@index');

//Sitemap Route
$router->get('/sitemap.xml','SitemapController@index');


$router->get('/test',function(){
    $URL = "https://ssstiktok.io/";
    $resp = request($URL);
    $token = search_input($resp,'token');
    $locale = search_input($resp,'locale');
//    print_r([
//       'token'=>$token,
//       'locale'=>$locale
//    ]);

    $wrapper = wrapper_request("https://ssstiktok.io/api/1/fetch",[
       "id"=>"https://www.tiktok.com/@zayn_inzi/video/6860160457022409985?lang=en",
        "token"=>$token,
        "locale"=>$locale
    ]);

    echo $wrapper;
});

//Home Route
$router->all('/(.*)','HomeController@index');