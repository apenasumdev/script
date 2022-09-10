<?php


namespace App\Services;

use App\Models\Video;
use Exception;
use Lite\Auth\Gate;
use UnexpectedValueException;

class TikTok
{
    private $pageProps = [];
    private $shareMeta = [];
    private $itemProps = [];
    private $attrs = [];

    private static $HEADERS = [];

    const WRAPPER_URL = "https://codespikex.com/tiktok-api/v2";

    public function __construct($pageProps, $shareMeta, $itemProps)
    {
        $this->pageProps = $pageProps;
        $this->shareMeta = $shareMeta;
        $this->itemProps = $itemProps;
    }

    public static function scrape($videoUrl)
    {
        $validate = filter_var($videoUrl, FILTER_SANITIZE_STRING);

        $isAdmin = false;
        if(Gate::auth())
            $isAdmin = Gate::auth()->isAdmin();

        $somethingError = $isAdmin ? "Couldn't fetch video; either your proxy is dead or you're not using proxies at all." : "Something went wrong! Video not found.";

        if (!$validate) {
            throw new UnexpectedValueException("Url is Invalid", 500);
        }


        $_json = null;
        $try = 0;

        try {
            do {
                self::generateHeaders();

                $resp = request($videoUrl, false, self::$HEADERS, get_random_proxy());



                if(strpos($resp,'rel="canonical" href="https://www.tiktok.com') !== -1){

                    $startAt = preg_quote('rel="canonical" href="', '/');
                    $endEnd = preg_quote('"/>', '/');

                    preg_match("/$startAt(.*?)$endEnd/", $resp, $matches);

                    if (is_array($matches) && isset($matches[1])) {
                        $originalURL = $matches[1];

                        $resp = request($originalURL, false, self::$HEADERS, get_random_proxy());
                    }
                }



                $_json = self::searchJson($resp);

                $try++;

            } while (!$_json && $try <= 1);

        }catch (Exception $e){
            error_log($e->getMessage());
            throw new UnexpectedValueException($somethingError, 500);
        }

        if (!$_json) {
            error_log('Not a json');
            throw new UnexpectedValueException($somethingError, 500);
        }

            $json = json_decode($_json[1], true);

            if (data_get($json, 'props.pageProps.statusCode')) {
                throw new UnexpectedValueException("Something went wrong! Cannot scrape video.", 500);
            }

            $pageProps = data_get($json, 'props.pageProps');
            $shareMeta = data_get($pageProps, 'seoProps', []);
            $itemProps = data_get($pageProps, 'itemInfo.itemStruct');

            if (is_null($pageProps) || is_null($shareMeta) || is_null($itemProps)) {
                throw new UnexpectedValueException("Something went wrong!", 500);
            }

            return (new static($pageProps,$shareMeta,$itemProps))->getMeta()->getUser();
    
    }

    public static function scrapeFromJSON($json){
        $shareMeta = data_get($json, 'shareMeta');
        $itemProps = data_get($json, 'itemStruct');

        if (is_null($shareMeta) || is_null($itemProps)) {
            throw new UnexpectedValueException("Something went wrong!", 500);
        }

        self::generateHeaders();

        return (new static([],$shareMeta,$itemProps))->getMeta()->getUser();
    }

    /**
     * GENERATE STATIC HEADERS
     */
    protected static function generateHeaders(){
        $tt_webid_v2 = "68".make_id(17);

        $userAgent = random_user_agent();

        self::$HEADERS = [
            "user-agent: {$userAgent}",
            "Referer: https://www.tiktok.com/",
            "Cookie: tt_webid_v2={$tt_webid_v2}",
            "method: GET",
            "scheme: https",
            "accept: application/json, text/plain, */*",
            "accept-encoding: gzip, deflate, br",
            "accept-language: en-US,en;q=0.9",
            "origin: referrer",
            "referer: referrer",
            "sec-fetch-dest: empty",
            "sec-fetch-mode: cors",
            "sec-fetch-site: same-site",
            "sec-gpc: 1",
            
        ];
    }

    protected static function searchJson($data){
        preg_match("/<script id=\"__NEXT_DATA__\" (?:.*?)>(.*?)<\/script>/", $data, $matches);
		return $matches;
	}


    public function getMeta()
    {
        //Meta Data
        data_set($this->attrs, 'meta.title', data_get($this->shareMeta, 'metaParams.title', config('app.name')));
        data_set($this->attrs, 'meta.desc', data_get($this->shareMeta, 'metaParams.description', config('app.desc')));
        data_set($this->attrs, 'meta.cover', data_get($this->itemProps, 'video.cover'));
        data_set($this->attrs, 'meta.id', data_get($this->itemProps, 'id'));
        data_set($this->attrs, 'meta.caption', data_get($this->itemProps, 'desc'));
        data_set($this->attrs, 'meta.uploaded_at', date("Y-m-d H:i:s", data_get($this->itemProps, 'createTime')));
        return $this;
    }

    public function getUser()
    {
        data_set($this->attrs, 'user.name', data_get($this->itemProps, 'author.nickname'));
        data_set($this->attrs, 'user.username', data_get($this->itemProps, 'author.uniqueId'));
        data_set($this->attrs, 'user.cover', data_get($this->itemProps, 'author.avatarMedium'));
        return $this;
    }

    public function getVideo()
    {
        $downloadURL = data_get($this->itemProps, 'video.downloadAddr');
        $playURL = data_get($this->itemProps, 'video.playAddr');

        $videoURL = !is_null($downloadURL) ? $downloadURL : $playURL;

        data_set($this->attrs, 'video.cover', data_get($this->itemProps, 'video.cover'));
        data_set($this->attrs, 'video.original', $videoURL);
        
//        if ($include_nwm) {
//            data_set($this->attrs, 'video.nwm', self::getNoWatermark(data_get($this->attrs, 'video.original')));
//        }
        return $this;
    }

    public function getMusic()
    {
        if (data_get($this->itemProps, 'music')
        && data_get($this->itemProps, 'music.id')) {
            $music = data_get($this->itemProps, 'music');

            data_set($this->attrs, 'music.id', data_get($music, 'id'));
            data_set($this->attrs, 'music.title', data_get($music, 'title'));
            data_set($this->attrs, 'music.author', data_get($music, 'authorName'));
            data_set($this->attrs, 'music.url', data_get($music, 'playUrl'));
            data_set($this->attrs, 'music.cover', data_get($music, 'coverMedium'));
        } else {
            data_set($this->attrs, 'music', null);
        }

        // $music_id = data_get($this->pageProps,'videoData.musicInfos.musicId');
        // $music_title = data_get($this->pageProps,'videoData.musicInfos.musicName');
        // $music_title_str = str_replace(' ','-',$music_title);
        // $music_url = "https://www.tiktok.com/music/{$music_title_str}-{$music_id}";
        // $music = self::getMusicData($music_url);
        // if(!is_null($music)) {
        // 	//Setting Music Data
        // 	data_set($this->attrs, 'music.id',    data_get($music,'id'));
        // 	data_set($this->attrs, 'music.title',      data_get($music,'title'));
        // 	data_set($this->attrs, 'music.author',      data_get($music,'author'));
        // 	data_set($this->attrs, 'music.cover',      data_get($music,'cover'));
        // 	data_set($this->attrs, 'music.url',      data_get($music,'url'));

        // }else data_set($this->attrs,'music',null);

        return $this;
    }

    public function getStats()
    {
        data_set($this->attrs, 'stats.comments', data_get($this->itemProps, 'stats.commentCount'));
        data_set($this->attrs, 'stats.likes', data_get($this->itemProps, 'stats.diggCount'));
        data_set($this->attrs, 'stats.shares', data_get($this->itemProps, 'stats.shareCount'));
        data_set($this->attrs, 'stats.play', data_get($this->itemProps, 'stats.playCount'));
        return $this;
    }

    public function toArray($property = null)
    {
        if (is_null($property)) {
            return $this->attrs;
        } else {
            return data_get($this->attrs, $property);
        }
    }

    public function toJson($property = null)
    {
        if (is_null($property)) {
            return json_encode($this->attrs);
        } else {
            return data_get($this->attrs, $property) === null ?
                null : json_encode(data_get($this->attrs, $property));
        }
    }

    public function get($property)
    {
        return data_get($this->attrs, $property);
    }

    /**
     * @param Video|TikTok $video
     * @return string|null
     */
    public static function getNoWatermark($video)
    {
        if ($video instanceof Video) {
            $video_link = $video->getAttribute('url');
        } else {
            $video_link = $video->get('video.original');
        }

        $api_version = config('app.api_version');

        if ($api_version != 'wrapper') {
            $resp = request($video_link);
            $matches = [];
            $pattern = '/vid:([a-zA-Z0-9]+)/';
            preg_match($pattern, $resp, $matches);
            if (count($matches) > 1) {
                if ($api_version == 'v1') {
                    return "https://api2-16-h2.musical.ly/aweme/v1/play/?video_id={$matches[1]}&vr_type=0&is_play_url=1&source=PackSourceEnum_PUBLISH&media_type=4";
                } else {
                    return self::use15API($video);
                }
            }
        } else {
            return self::wrapper($video);
        }
        return null;
    }

    /**
     * @param bool $overwrite
     * @return TikTok
     * @throws Exception
     */
    public function getNoWaterMarkVideo($overwrite = true)
    {
        try {
            $video_url = static::getNoWatermark($this);
            if ($overwrite) {
                data_set($this->attrs, 'video.nwm', $video_url);
            } else {
                data_set($this->attrs, 'video.nwm_downloadable', $video_url);
            }
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    /**
     * @param Video $video
     * @return string|null
     */
    public function fillWrapper()
    {

        $username = $this->get('user.username');
        $video_id = $this->get('meta.id');

        $license = config('license.code.code');
        $video_url = "https://www.tiktok.com/@{$username}/video/{$video_id}";

        $api_url =  self::WRAPPER_URL."?url=".urlencode($video_url)."&license=".$license;

        $req = request($api_url);
        $response = json_decode($req, true);


        $watermark_video = $response['watermark_link'] ?? null;
        $no_watermark_video = $response['no_watermark_link']
                            ?? $response['no_watermark_link_2']
                            ?? $response['no_watermark_link_3']
                            ?? null;

        $cover = "https://www.tiktok.com/api/img/?itemId={$video_id}&location=0";

        if($watermark_video)
            data_set($this->attrs, 'video.original', $watermark_video);
        if($no_watermark_video)
            data_set($this->attrs,'video.nwm', $no_watermark_video);
        if($cover){
            data_set($this->attrs,'video.cover', $cover);
            data_set($this->attrs,'meta.cover', $cover);
        }

        return $this;
    }

    /**
     * Use API 15
     * @param string $video_link
     * @return string
     * @deprecated Fails to scrap
     */
    protected static function use15API($video_link)
    {
        $url = "https://api.tiktokv.com/aweme/v1/playwm/?video_id={$video_link}";
        $resp = null;
        do {
            $resp = request_redirect($url);
        } while (strpos($resp, 'api'));
        return $resp;
    }

    /**
     * @param $music_url
     * @return array|null
     * @deprecated No longer needed
     */
    private static function getMusicData($music_url)
    {
        $resp = request($music_url);
        $result = search_json($resp);
        if (count($result)>1) {
            //JSON
            $json = json_decode($result[1], true);

            //VARIABLE
            if (!data_get($json, 'props.pageProps.musicData')) {
                return null;
            }
            $music = data_get($json, 'props.pageProps.musicData');
            //RESPONSE
            $musicData = [];
            data_set($musicData, 'id', data_get($music, 'musicId'));
            data_set($musicData, 'title', data_get($music, 'musicName'));
            data_set($musicData, 'author', data_get($music, 'authorName'));
            data_set($musicData, 'cover', data_get($music, 'covers')[0]);
            data_set($musicData, 'url', data_get($music, 'playUrl.UrlList')[0]);

            return $musicData;
        }
        return null;
    }

    /**
     * @param bool $overwrite
     * @return TikTok
     * @throws Exception
     */
    public function downloadVideo($overwrite = true)
    {

        try {
            $ch = curl_init();
            $options = array(
                CURLOPT_URL => $this->get('video.original'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_FOLLOWLOCATION => true,
//                CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.80 Safari/537.36',
                CURLOPT_ENCODING => "utf-8",
                CURLOPT_AUTOREFERER => false,
//                CURLOPT_REFERER => 'https://www.tiktok.com/',
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_HTTPHEADER => self::$HEADERS
            );
            curl_setopt_array($ch, $options);
            if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
                curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            }
            $data = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $date = date('U');

            $video_path = STORAGE_PATH . "/videos/";

            $filename = $date . '-' . $this->get('meta.id') . ".mp4";
            $d = fopen($video_path . $filename, "w");
            fwrite($d, $data);
            fclose($d);

            if ($overwrite) {
                data_set($this->attrs, 'video.original', base_url() . STORAGE_URL . '/videos/' . $filename);
            } else {
                data_set($this->attrs, 'video.original_downloadable', base_url() . STORAGE_URL . '/videos/' . $filename);
            }

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * @param bool $overwrite
     * @return $this
     * @throws Exception
     */
    public function downloadCover($overwrite = true)
    {
        try {
            $date = date('U');
            $video_content = request($this->get('video.cover'));
            $cover_path = STORAGE_PATH . "/covers/";

            if (!file_exists($cover_path)) {
                mkdir($cover_path, 0777);
            }

            $file_name = "{$date}-{$this->get('meta.id')}.jpg";
            file_put_contents(path($cover_path.$file_name), $video_content);
            if ($overwrite) {
                data_set($this->attrs, 'video.cover', base_url().STORAGE_URL.'/covers/'.$file_name);
            } else {
                data_set($this->attrs, 'video.cover_downloadable', base_url().STORAGE_URL.'/covers/'.$file_name);
            }

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param bool $overwrite
     * @return $this
     * @throws Exception
     */
    public function downloadAvatar($overwrite = true)
    {
        $this->getUser();

        try {
            $img = file_get_contents($this->get('user.cover'));
            $img_path = STORAGE_PATH . "/user/";
            if (!file_exists($img_path)) {
                mkdir($img_path, 0777);
            }

            $file_name = $this->get('user.username').'.jpg';

            file_put_contents(path($img_path.$file_name), $img);

            if ($overwrite) {
                data_set($this->attrs, 'user.cover', base_url().STORAGE_URL.'/user/'.$file_name);
            } else {
                data_set($this->attrs, 'user.cover_downloadable', base_url().STORAGE_URL.'/user/'.$file_name);
            }

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
