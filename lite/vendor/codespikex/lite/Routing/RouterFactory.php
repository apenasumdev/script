<?php

namespace Lite\Routing;

final class RouterFactory {

    /**
     * @var Router
     */
    private $router = null;

    /**
     * By Static instance
     */
    public static function response(){
        return new static();
    }
    /**
     * Routing Constructor
     */
    public function __construct(){
        $this->router = new Router();
        //Setting Up Namespaces
        $this->router->setNamespace('App\Controllers');
        $this->router->setMiddlewareNamespace('App\Middlewares');

        //Start
        $this->getRoutes('middleware');
        $this->getApiRoutes();
        $this->getRoutes('web');

        $this->run();
    }

    public function run(){
    	$this->router->run();
    }

	/**
	 * Get Web Routes
	 * @param string $route
	 */
    public function getRoutes($route = 'route'){
    	if(! file_exists(app_path("routes/{$route}.php")))
    		return;
        extract(['router'=>&$this->router]);
        require_once app_path("routes/{$route}.php");
    }
    public function getApiRoutes(){
	    $this->router->mount('/api',function() {
	    	$this->getRoutes('api');
	    });
    }
}