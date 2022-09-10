<?php


namespace App\Controllers\API;

use App\Models\Page;
use Lite\Http\Controller;
use App\Resources\{PageResource, PagesResource};

class PageController extends Controller
{

	const FREEZED_PAGES = ['how-to-download','privacy-policy','terms-of-services'];

	/**
	 * Controller index
	 * @param null $action
	 */
	public function index($action = null){

		$accepted_actions = ['edit','add','load','delete'];
		if(!in_array($action,$accepted_actions))
			return response(['error'=>'Invalid Action!'],500);

		switch($action){
			case 'add':
				$this->add();
				break;
			case 'edit':
				$this->edit();
				break;
			case 'delete':
				$this->delete();
				break;
			default:
				$this->load();
				break;
		}
		return response(['error'=>'Something went wrong'],500);
	}

	/**
	 * Load Pages
	 */
	private function load(){
		try{
			$pages = Page::all('slug')->map(function ($page){return $page[0];});

			return response(new PagesResource($pages));
			
		}catch(\Exception $e){
			response(['error'=>$e->getMessage()],500);
		}
	}
	/**
	 * Add Page
	 */
	private function add(){

		$this->checkPermissions();

		$title = $_POST['title'] ?? null;
		$slug = $_POST['slug'] ?? null;
		$excerpt = $_POST['excerpt'] ?? null;
		$body = $_POST['body'] ?? null;

		if(is_null($title) 
		|| is_null($slug) 
		|| is_null($excerpt) 
		|| is_null($body))
			return response(['error'=>'Something is missing!'],500);
		try {

			$slug_taken = Page::find(['slug'=>$slug]);
			if($slug_taken)
				return response(['error'=>'Slug Taken'],500);

			$page = Page::create([
				'title'=>$title,
				'slug'=>$slug,
				'excerpt'=>$excerpt,
				'body'=>$body
			]);
			return response([$page->slug=>new PageResource($page)]);
		}catch(\Exception $e){
			return response(['error'=>$e->getMessage()],500);
		}
	}

	/**
	 * Edit page
	 */
	private function edit(){
		$this->checkPermissions();

		$id = $_POST['id'] ?? null;
		$title = $_POST['title'] ?? null;
		$excerpt = $_POST['excerpt'] ?? null;
		$body = $_POST['body'] ?? null;


		if(is_null($id))
			return response(['error'=>'Invalid ID'],500);

		try {
			$page = Page::find(['id'=>$id]);

			if(!$page)
				return response(['error'=>'Cannot find page with provided id'],500);



			if($title)
				$page->title = $title;
			if($excerpt)
				$page->excerpt = $excerpt;
			if($body)
				$page->body = $body;
			$page->save();

			return response([$page->slug=>new PageResource($page)]);

		} catch (\Exception $e) {
			return response(['error'=>$e->getMessage()],500);
		}
	}
	private function delete(){
		$this->checkPermissions();

		$id = $_POST['id'] ?? null;
		if(is_null($id))
			return response(['error'=>'Id is invalid'],500);

		try {
			$page = Page::find($id);

			if($page) {
				if(in_array($page->slug,self::FREEZED_PAGES))
					return response(['error'=>'This page can`t be deleted.'],500);
				$page->delete();
			}
			return $this->load();

		} catch (\Exception $e) {
			return response(['error'=>$e->getMessage()],500);
		}
	}
}