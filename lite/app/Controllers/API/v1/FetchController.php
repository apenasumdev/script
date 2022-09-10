<?php

namespace App\Controllers\API\v1;

use App\Models\Video;
use App\Resources\VideoResource;
use App\Services\TikTok;
use Exception;
use PDOException;

class FetchController {

    public function index($json = null, $byJson = false){
        $url = $_GET['url'] ?? null;

		try{
		    if($byJson)
		        $scrapper = TikTok::scrapeFromJSON($json);
		    else
			    $scrapper = TikTok::scrape($url);

			$scrapper
                ->getMeta()
                ->getVideo()
                ->getUser()
                ->getStats()
                ->fillWrapper()
                ->downloadAvatar();

			$meta = $scrapper->toArray('meta');
			$user = $scrapper->toArray('user');
			$stats = $scrapper->toArray('stats');

			$videoModel = Video::find(['video_id'=>$meta['id']]);

			if($videoModel){

			    $downloadedVideo = false;
			    $downloadedCover = false;

//                try {
//
//                    if(!$videoModel->url
//                        || !filter_var($videoModel->url,FILTER_VALIDATE_URL)
//                        || str_contains($videoModel->url, 'tiktokcdn')
//                    ) {
//                        $scrapper->downloadVideo();
//                        $downloadedVideo = true;
//                    }
//                    if(!$videoModel->cover
//                        || !filter_var($videoModel->cover,FILTER_VALIDATE_URL)
//                        || str_contains($videoModel->cover, 'tiktokcdn')
//                        || str_contains($videoModel->cover,'no-image.jpg')
//                    ) {
//                        $scrapper->downloadCover();
//                        $downloadedCover = true;
//                    }
//
//                }catch (Exception $e){
//                    error_log($e->getMessage());
//                    return response(['log'=>$e->getMessage()],500);
//                }

				$videoModel->stats = $stats;
				$videoModel->user = $user;

				if($downloadedCover)
				    $videoModel->cover = $scrapper->get('video.cover');

				$videoModel->title = $meta['title'];
				$videoModel->caption = $meta['caption'];

				if($downloadedVideo)
				    $videoModel->url = $scrapper->get('video.original');

				$videoModel->uploaded_at = $meta['uploaded_at'];
				$videoModel->dl_count = $videoModel->dl_count + 1;
				$videoModel->save();


			} else {

				$scrapper->getMusic();
				$music = $scrapper->toArray('music');

//				try {
//                    $scrapper->downloadVideo()->downloadCover();
//                }catch (Exception $e){
//				    error_log($e->getMessage());
//				    return response(['log'=>$e->getMessage()],500);
//                }

				$videoModel = Video::create([
					'video_id'=> $scrapper->get('meta.id'),
					'title'=> $scrapper->get('meta.title'),
					'cover'=> $scrapper->get('video.cover'),
					'caption'=> $scrapper->get('meta.caption'),
					'url'=> $scrapper->get('video.original'),
					'url_nwm'=> $scrapper->get('video.nwm') ?? '',
					'uploaded_at'=> $scrapper->get('meta.uploaded_at'),
					'music'=> $music,
					'user'=> $user,
					'stats'=> $stats
				]);
			}

			return response(new VideoResource($videoModel));

		}
		catch (PDOException $e){
			error_log($e->getMessage(),$e->getCode());
			response(['error'=>'Something went wrong! Database error.'], 500);
		}
		catch (Exception $e) {
			response(['error' => $e->getMessage()], 500);
		}
    }


    public function fetchByJson(){
        $json = $_GET['json'] ?? null;

        if(!$json)
            return response(['error'=> 'Something went wrong. Data not found!'],500);

        $json = json_decode(urldecode($json),true);

        return $this->index($json, true);
    }

	public function fetch($video_id){

    	$video = Video::find(['video_id'=>$video_id]);
    	if(!$video)
    		return response(['error'=>'Somthing went wrong! Video not found'],500);

		$original_video = true;
		$nwm_video = true;

		//Scrape original Video


        if(     !$video->url
            || !$video->cover
            || !filter_var($video->url,FILTER_VALIDATE_URL)
            || !filter_var($video->cover,FILTER_VALIDATE_URL)
        ) {

//            try {
//
//            $scrapper = TikTok::scrape($video->video_url)->getVideo();
//
//            if(!$video->url) {
//                $video->url = $scrapper->downloadVideo()->get('video.url');
//                $original_video = true;
//            }
//            if(!$video->cover)
//                    $video->cover = $scrapper->downloadCover()->get('video.cover');
//
//
//            $video->save();
//            }catch (\Exception $e){
//                error_log($e->getMessage());
//                //return response(['error'=>$e->getMessage()],500);
//                $original_video = false;
//            }
        }


        try {
        //Get NOWATERMARK URL
        $nwm_url = TikTok::scrape($video->video_url)
            ->getMeta()
            ->getUser()
            ->getVideo()
            ->fillWrapper()
            ->get('video.nwm');

        $video->url_nwm = $nwm_url;
        $video->save();

        }catch (Exception $e){
            $nwm_video = false;
            error_log($e->getMessage());
        }


        if($video->url_nwm === $video->url)
            $original_video = false;

		return response(['original'=>$original_video,'nwm'=>$nwm_video]);
	}

	/**
	 * @param Video $video
	 * @param string $url
	 * @throws Exception
     * @deprecated since 1920
	 */
	protected function saveVideo(&$video,$url){
		try{
			$date = date('U');
			$video_content = file_get_contents($url);
			$video_path = STORAGE_PATH . "/videos/";
			$file_name = "{$date}-{$video->video_id}.mp4";
			file_put_contents(path($video_path.$file_name),$video_content);
			$video->url_nwm = $file_name;
			$video->save(true);
		}catch (Exception $e){
			throw $e;
		}
	}
}