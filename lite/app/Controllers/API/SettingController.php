<?php


namespace App\Controllers\API;


use Lite\Http\Controller;
use Lite\Support\Arr;
use Lite\Utility\Config;

class SettingController extends Controller
{
	const ACCEPTED_ACTIONS = ['menu','code','setting'];

	public function index($action){

		$this->checkPermissions();

		if(!in_array($action,static::ACCEPTED_ACTIONS))
			return response(['error'=>'Action is invalid'],500);

		switch ($action){
			case 'menu':
				return $this->updateMenus();
				break;
			case 'code':
				return $this->updateCodes();
				break;
			case 'setting':
				return $this->updateSetting();
				break;
		}
	}


	public function updateMenus(){

		$menus = $_POST['menu'] ?? null;

		if(!$menus)
			return response(['error'=>'Menu items are empty'],500);

		$menu_config = new Config('menu');

		try{
			$menus = json_decode($menus,true);

			$menus = collect($menus)->map(function ($menu){
				$external = $menu['external'] == 'true' ? true : false;
				return array_merge($menu,['external'=>$external]);
			})->toArray();

			$menu_config->clear();

			foreach ($menus as $key => $menu)
				$menu_config->set((int)$key,$menu);

			$menu_config->save();

			return response($menu_config->getAttributes());
		}catch (\Exception $e){

			$menu_config->restore();

			return response(['error'=>$e->getMessage()],500);
		}
	}

	public function updateCodes(){

		$ads = $_POST['ads'] ?? null;
		$code = $_POST['code'] ?? '';

		if($code) $code = base64_decode($code);

		if(!$ads)
			return response(['error'=>'Ads items are empty'],500);

		$ads_config = new Config('code');

		try{
			$ads = json_decode($ads,true);

			$new_ads = [];

			foreach($ads as $key => $value){
				$new_ads[(int)$key] = $value;
			}

			$ads_config->clear();
			$ads_config->set('ads',$new_ads);
			$ads_config->set('code',$code);

			$ads_config->save();

			return response($ads_config->getAttributes());

		}catch (\Exception $e){

			$ads_config->restore();

			return response(['error'=>$e->getMessage()],500);
		}
	}

	public function updateSetting(){
		$app = $_POST['app'] ?? null;
		$socials = $_POST['socials'] ?? null;
		if(!$app || !$socials)
			return response(['error'=>'Something is missing!'],500);

		$app_config = new Config('app');
		$social_config = new Config('social');
		try{
			$app = json_decode($app,true);
			$socials = json_decode($socials,true);


			if(isset($app['cover']))
				unset($app['cover']);

			foreach ($app as $key => $value)
				$app_config->set($key,$value);

			$app_config->save();

			$social_config->clear();

			foreach ($socials as $key => $value) {
				$socials[$key]['link'] = $value['link'];
			}
			foreach ($socials as $key => $social)
				$social_config->set((int)$key,$social);
			$social_config->save();
			response(['app'=>$app_config->getAttributes(),'socials'=>$social_config->getAttributes()]);

		}catch (\Exception $e){

			$app_config->restore();
			$social_config->restore();

			response(['error'=>$e->getMessage()],500);
		}
	}
}