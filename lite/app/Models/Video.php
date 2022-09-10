<?php


namespace App\Models;


use Lite\Database\Eloquent;
use Lite\Support\Collection;

/**
 * Class Video
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property string $video_id
 * @property string $caption
 * @property string $cover
 * @property string $url
 * @property string $url_nwm
 * @property array $music
 * @property array $user
 * @property array stats
 * @property \DateTime $uploaded_at
 * @property int dl_count
 * @property string $video_url
 */

class Video extends Eloquent
{
	protected $casts = [
		'music'=> 'array',
		'user'=> 'array',
		'stats'=> 'array',
		'dl_count'=> 'int'
	];

	protected static $jsonCast = [
		'user', 'music', 'stats'
	];

	protected $appends = ['video_url','share_url'];

	public function getVideoUrlAttribute(){
		$user = $this->user;
		if(is_string($user))
			$user = json_decode($user,true);
		$username = data_get($user,'username');
		return "https://www.tiktok.com/@{$username}/video/{$this->video_id}";
	}

	public function getShareUrlAttribute(){
		return base_url().'/video/'.$this->video_id;
	}

	public static function trending($limit = 30,$page = 1){
		$collection = self::db()->select('*')
					->orderBy('dl_count','desc')
					->pagination($limit,$page)
					->getAll();
		if($collection instanceof Collection){
			if($collection->isNotEmpty()){
				$collection = $collection->groupBy('video_id')
					->map(function($video){
						return $video[0];
					});
			}
		}
		return $collection;
	}

	public static function recent($limit = 100,$page = 1){
		$collection = self::db()->select('*')
					->orderBy('id','desc')
					->pagination($limit,$page)
					->getAll();
		if($collection instanceof Collection) {
			if ($collection->isNotEmpty()) {
				$collection = $collection->groupBy('video_id')
					->map(function ($video) {
						return $video[0];
					});
			}
		}
		return $collection;
	}
}