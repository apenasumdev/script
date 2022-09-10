<?php

namespace App\Controllers;

use App\Models\Page;
use App\Models\Video;
use App\Resources\VideoResources;
use Exception;
use Lite\Auth\Gate;

class HomeController {


	const ACCEPTED_ROUTES = ['video','page'];
	/**
	 * @var array
	 */
	private $collection = [];

	/**
	 * Home Controller view
	 * @param string|null $route
	 * @throws Exception
	 */
    public function index($route = null){

		$this->populateCollection($route);

        view('home',$this->collection);
    }
	/**
	 * Populate Collection
	 * @param string|null $route
	 * @return null|mixed|void
	 */
    private function populateCollection($route = null){
	    /**
	     * App Data
	     */
	    $app = config('app',true,['token']);
		$this->collection['app'] = array_merge($app,['cover'=> base_url() . "/assets/images/{$app['cover']}"]);


		$this->collection['pages'] = Page::all('slug')
			->map(function ($page){
				return $page[0];
			})
			->toArray();

	    $this->collection['menus'] = config('menu');
		$this->collection['codes'] = config('code');
		$this->collection['socials'] = config('social');


		if(Gate::auth()){
			if(file_exists(ROOT_PATH.DIRECTORY_SEPARATOR.'install'))
				$this->collection['error'] = 'Please delete install directory!';
		}


		if(!$route)
			return;






		$_route = explode('/',$route);
		if(!isset($_route[0]) || !isset($_route[1]))
			return;

		list($action, $id) = $_route;

		if(!in_array($action,self::ACCEPTED_ROUTES))
			return;

		if($action === 'video')
			$this->collectVideo($id);
		if($action === 'page')
			$this->collectPage($id);
    }

    private function collectVideo($video_id){
		$video = Video::find(['video_id'=>$video_id]);
		if(!$video){
			return redirect('404');
		}
		$video->title = "{$video->title} - {$this->collection['app']['name']}";
		$this->collection['video'] = array_merge($video->toArray(),['url'=>base_url().'/video/'.$video->video_id]);
    }

    private function collectPage($slug){
		if(!isset($this->collection['pages']))
			return;

		$this->collection['page'] = collect($this->collection['pages'])
			->where('slug','=',$slug)
			->values()
			->map(function($page){
				return [
					'title'=> "{$page['title']} - {$this->collection['app']['name']}",
					"desc"=> $page['excerpt'],
					'url'=> base_url().'/page/'.$page['slug']
				];
			})
			->toArray();
    }


    public function getRecentVideos(){

    	$limit = $_GET['limit'] ?? 100;
    	$limit = intval($limit);
    	$page = $_GET['page'] ?? 1;
    	$page = intval($page);

    	try {
		    return response(new VideoResources(Video::recent($limit,$page)));
	    }catch(Exception $e){
    		return response(['error'=>$e->getMessage()],500);
	    }

    }

    public function getTrendingVideos(){

	    $limit = $_GET['limit'] ?? 100;
	    $limit = intval($limit);
	    $page = $_GET['page'] ?? 1;
	    $page = intval($page);

	    try {
		    return response(new VideoResources(Video::trending($limit,$page)));
	    }catch(Exception $e){
		    return response(['error'=>$e->getMessage()],500);
	    }

    }
}