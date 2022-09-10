<?php


namespace App\Jobs;

use App\Models\Video;
use App\Services\TikTok;
use Lite\Cron\Job;

/**
 * Class CoverChecker
 * @package App\Jobs
 * @deprecated
 */
class CoverChecker implements Job
{

	public function run()
	{

        try {
            $videos = Video::all()->map(function ($video) {
                return $video[0];
            });

            $errors = [];
            $updated = [];
            foreach ($videos as $video) {
                $statusCover = image_exists($video->cover);
                $statusAvatar = image_exists($video->user['cover']);
                if ($video->cover === base_url().asset('images/no-image.jpg')) {
                    $statusCover = false;
                }
                if ($video->user['cover'] === base_url().asset('images/no-image.jpg')) {
                    $statusAvatar = false;
                }

                if (!$statusCover || !$statusAvatar) {
                    try {
                        $scrapper = TikTok::scrape($video->video_url)
                        ->getMeta()
                        ->getVideo(false)
                        ->getUser();

                        if (!$statusCover) {
                            $video_cover = $scrapper
                            ->downloadCover()
                            ->get('video.cover');

                            $video->cover = $video_cover;
                        }

                        if (!$statusAvatar) {
                            $user = $scrapper
                            ->downloadAvatar()
                            ->toArray('user');

                            $video->user = $user;
                        }

                        $video->save();

                        $updated[] = $video->video_id;
                    } catch (\Exception $e) {
                        error_log($e->getMessage(), $e->getCode());

                        if (!$statusCover) {
                            $video->cover = base_url().asset('images/no-image.jpg');
                        }

                        if (!$statusAvatar) {
                            $video->user['avatar'] = base_url().asset('images/no-image.jpg');
                        }

                        $video->save();

                        $errors[$video->video_id] = $e->getMessage();
                    }
                }
            }

            if (count($errors) > 0) {
                foreach ($errors as $video_id => $error) {
                    echo $video_id . ' => ' . $error.'<br/>';
                }
            } else {
                foreach ($updated as $video_id) {
                    echo $video_id .'<br/>';
                }
            }
        }catch(\Exception $e){
			print_r($e);
		}
	}
}