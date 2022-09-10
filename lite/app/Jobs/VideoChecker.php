<?php


namespace App\Jobs;


use App\Models\Video;
use Lite\Support\Carbon;
use Lite\Cron\Job;

class VideoChecker implements Job
{

	const VIDEO_DIR = STORAGE_PATH . '/videos';
	public function run()
	{

            $hoursToAdd = config('cron.delete_video_after');
            //Scanning Dir
            $videos = array_diff(scandir(self::VIDEO_DIR), array('.', '..'));
            $videos = array_values($videos);
            $videos = array_map(function ($video) {
                return str_replace('.mp4', '', $video);
            }, $videos);
            //Looping List
            foreach ($videos as $video) {
                list($date, $video_id) = explode('-', $video);
                //Create data and add hours in it
                $video_expires_at = Carbon::createFromFormat('U', $date)
                ->addHours($hoursToAdd)
                ->timezone(date_default_timezone_get());
                //If Time is passed
                if ($video_expires_at->isPast()) {
                    //Delete the video
                    unlink(self::VIDEO_DIR . '/' . $video . '.mp4');
                    //Update the model if exists
                    $video_model = Video::find(['video_id'=>$video_id]);
                    if ($video_model) {
                        $video_model->url = '';
                        $video_model->url_nwm = '';
                        $video_model->save(true);
                    }
                }
            }
    
	}
}