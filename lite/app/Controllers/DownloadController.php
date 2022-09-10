<?php


namespace App\Controllers;

use App\Models\Video;
use App\Services\TikTok;

class DownloadController
{
	const ACCEPTED_TYPES = ['music','video'];

	public function index(){
		$type = $_GET['type'] ?? null;
		if(!in_array(strtolower($type),self::ACCEPTED_TYPES))
			return response(['error'=>'Invalid Request!'],500);

		$id = $_GET['id'] ?? null;
		if(!$id)
			return response(['error'=> 'ID is required!'],500);

		$video = Video::find(['video_id'=>$id]);
		if(!$video)
			return response(['error'=>'Record not found!'],500);

		switch ($type){
			case 'music':
				return $this->downloadMusic($video);
				break;
			case 'video':
				return $this->downloadVideo($video);
				break;
		}
		return response(['error'=>'Something went wrong!'],500);
	}


	/**
	 * @param Video $video
	 */
	public function downloadVideo($video){
		$nwm = $_GET['nwm'] ?? 'false';
		$url = $nwm == 'true' ? $video->url_nwm : $video->url;

		if(is_null($url))
			return response(['error'=>'Download link is invalid'],500);

		$app_name = config('app.name');
		$title = "Download {$video->title} - {$app_name}";
		//Download File
		if($nwm == 'true'){

			$is_url = filter_var($url,FILTER_VALIDATE_URL);

			if($is_url){
				$this->download_file($url,$title);
			}else {
				$nwm_url = path(STORAGE_PATH . '/videos/' . $url);
				$this->download_file($nwm_url, $title, 'mp4', true);
			}
		}else {
			$this->download_file($url, $title);
		}
	}

	/**
	 * @param Video $video
	 */
	public function downloadMusic($video){
		$url = data_get($video,'music.url');
		if(!$url)
			return response(['error'=>'Music not found!'],500);

		$music_title = data_get($video,'music.title','Music');
		$app_name = config('app.name');
		$title = "Download {$music_title} - {$app_name}";
		//Download File
		$this->download_file($url,$title,'mp3');
	}

	/**
	 * Download File
	 * @param string $url
	 * @param string $title
	 * @param string $ext
	 * @param boolean $selfHosted
	 */
	private function download_file($url,$title,$ext = 'mp4',$selfHosted = false){
		$size = null;
		if($selfHosted) {
			$handle = fopen($url, "rb");
			$file = fread($handle, filesize($url));
			$size = filesize($url);
			fclose($handle);
		}
		else {
            list($file,$size) = request($url,true);
        }
//		else if (strpos($url,'api2-16-h2') !== -1){
//		    list($file,$size) = request($url,true);
//        }
//        else
//        {
//            $file = file_get_contents($url);
//            $size = strlen($file);
//        }

        if(is_array($size))
            $size = $size[0];

		header("Content-type: application/octet-stream");
		if($size)
			header("Content-Length: ".$size);
		header("Content-Disposition: attachment; filename=".$title.'.'.$ext);
		echo $file;
	}
}