<?php

use Lite\Contracts\Http\Resource;
use Lite\Database\Eloquent;
use Lite\Support\Arr;
use Lite\Support\Collection;

if(! function_exists('path')){

	/**
	 * Create path string
	 * @param string $path
	 * @return string|string[]|null
	 */
    function path($path){
        return preg_replace('/(\\\|\/)/',DIRECTORY_SEPARATOR,$path);
    }
}

if(! function_exists('root_path')){

	/**
	 * Path to root directory
	 * @param string $path
	 * @return string|string[]|null
	 */
    function root_path($path = null,$main = true){
		$root_path = ROOT_PATH;
        if(!$path) return ROOT_PATH;
        return path(ROOT_PATH . '/' . $path);
    }
}

if(! function_exists('app_path')){

	/**
	 * Path to app directory
	 * @param string $path
	 * @return string|string[]|null
	 */
    function app_path($path = null){
        if(!$path) return APP_PATH;
        return path(APP_PATH . '/' . $path);
    }
}

if(! function_exists('config')){
    
    /**
     * Get configs
     * @param string $path
     * @param boolean $assoc
     * @param array $ignore
     * @return null|mixed
     */
    function config($path,$assoc = true, $ignore = null){
        //Getting depth
        $parts = explode('.',$path);		
        //Creating path
        $file = app_path("config/{$parts[0]}.php");
        //removing path element
        unset($parts[0]);
        //return null if file is invalid
        if(!file_exists($file))
            return null;
        
        //getting content
        $config = require $file;
        //filter nested array
        $result = $config;

        foreach($parts as $part){
            if($result[$part])
                $result = $result[$part];
            else {
                $result = null;
                break;
            }
        }
        //Remove ignored items
        if(is_array($result) && $ignore !== null)
            foreach ($ignore as $remove)
                unset($result[$remove]);

        //return if null
        if(is_null($result))
            return $result;

        //return associative array if assoc is true
        //else return object
        return $assoc ? $result : to_object($result);
    }
}

if(! function_exists('to_object')){

	/**
	 * Cast an array to object
	 * @param array $array
	 * @return object
	 */
	function to_object($array) {

		if (is_array($array))
			return (object) array_map(__FUNCTION__, $array);
		else
			return $array;
	}
}

if(!function_exists('request')){
    /**
     * @param string $url
     * @param boolean $include_size
     * @param bool $headers
     * @return boolean|string|array
     */
	function request($url,$include_size = false,$headers = [], $proxy = null){


		$ch = curl_init();
		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Mobile Safari/537.36',
			CURLOPT_ENCODING       => "utf-8",
			CURLOPT_AUTOREFERER    => false,
			CURLOPT_COOKIEJAR      => app_path('storage/cookie.txt'),
			CURLOPT_COOKIEFILE     => app_path('storage/cookie.txt'),
			CURLOPT_REFERER        => 'https://www.tiktok.com/',
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_MAXREDIRS      => 10,
		);

		

		curl_setopt_array( $ch, $options );


		if(is_string($proxy)){

			list($host, $credentials) = string_to_proxy($proxy);

            if (!empty($host) && !empty($credentials)) {
                curl_setopt($ch, CURLOPT_PROXY, $host);
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $credentials);
            }
		}

		if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
		  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		}
		$data = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		$size = null;
		if($include_size)
			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		
		
		curl_close($ch);

		if($include_size)
			return [$data,$size];


		return strval($data);



// 	    $client = new \GuzzleHttp\Client([
// 	        'verify'=> SSL_ENABLED,
//             'headers'=> array_merge(
//                 [
//                     'User-Agent'=> 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.47 Safari/537.36',
//                 ],
//                 $headers
//             ),
//             'allow_redirects'=> [
// 				'max'             => 5,
// 				'strict'          => false,
// 				'referer'         => false,
// 				'protocols'       => ['http', 'https'],
// 				'track_redirects' => true
// 			],
//             'proxy'=> $proxy,
//             'curl'=> [
//                 CURLOPT_AUTOREFERER=> true,
//                 CURLOPT_RETURNTRANSFER=> true,
//                 CURLOPT_SSL_VERIFYPEER=> false,
//                 CURLOPT_HEADER=> false
//             ]
//         ]);


// 	    $request = $client->request('GET',$url);

	

//         $body = (string)$request->getBody();

//         $size = $request->getHeader('Content-Length');

//         if($include_size)
// 			return [$body,$size];
		
//         return $body;
// //
//		$ch = curl_init();
//		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
//		//browser's user agent string (UA)
//        if(!$headers)
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.47 Safari/537.36');
//		curl_setopt($ch, CURLOPT_HEADER, false);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        if($headers)
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//		$data = curl_exec($ch);
//
//		$size = 0;
//
//		if($include_size)
//			$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
//
//		curl_close($ch);
//
//		if($include_size)
//			return [$data,$size];
//
//		return $data;

		// $ch = curl_init();
		// $options = array(
		// 	CURLOPT_URL            => $url,
		// 	CURLOPT_RETURNTRANSFER => true,
		// 	CURLOPT_HEADER         => false,
		// 	CURLOPT_FOLLOWLOCATION => true,
		// 	CURLOPT_USERAGENT => 'okhttp',
		// 	CURLOPT_ENCODING       => "utf-8",
		// 	CURLOPT_AUTOREFERER    => false,
		// 	CURLOPT_REFERER        => $url,
		// 	CURLOPT_CONNECTTIMEOUT => 30,
		// 	CURLOPT_SSL_VERIFYHOST => false,
		// 	CURLOPT_SSL_VERIFYPEER => false,
		// 	CURLOPT_TIMEOUT        => 30,
		// 	CURLOPT_MAXREDIRS      => 10,
			
		// );

		// if($headers) {
		// 	$options[CURLOPT_HTTPHEADER] = [
		// 		'Accept'=> 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
		// 			'Accept-encoding'=> 'en-US,en;q=0.9',
		// 			'Cache-control'=> 'no-cache'
		// 	];
			
		// }

		// curl_setopt_array( $ch, $options );
		//;

		// if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))
		// 	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		
		// $data = curl_exec($ch);

		
		// curl_close($ch);

		// if($include_size)
		// 	return [$data,$size];
		// return $data;


		// $curl = curl_init();
		// curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
		// //browser's user agent string (UA)
		// curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.47 Safari/537.36');
		// curl_setopt($curl, CURLOPT_HEADER, 0);
		// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($curl, CURLOPT_URL, $url);
		// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
		// curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// // curl_setopt($curl, CURLOPT_PROXY, 'http://zproxy.lum-superproxy.io:22225');
		// // curl_setopt($curl, CURLOPT_PROXYUSERPWD, 'lum-customer-ahtisham_kh-zone-tiktok:wfzpf7qo3so1');
		// $response = curl_exec($curl);
		// $size = null;
		// if($include_size)
		// 	$size = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		// curl_close($curl);
		// if($include_size)
		// 	return [$response,$size];
		// return $response;
	}
}

if(! function_exists('string_to_proxy')){

	/**
	 * Convert proxy string to host and credientials
	 * @param string $proxy
	 * @return array [$host,$credientials,$protocol]
	 */
	function string_to_proxy($proxy){
		$parts = explode('@',$proxy);
		$credentials = $parts[0];
		$host = $parts[1];
		$credentialsParts = explode('//',$credentials);
		$protocol = $credentialsParts[0] . '//';
		$credentials = $credentialsParts[1];
		$host = $protocol.$host;

		return [$host,$credentials,$protocol];
	}
}

if(! function_exists('request_redirect')){

	/**
	 * Get the redirect
	 * @param string $url
	 * @return string|array
	 */
	function request_redirect($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		//browser's user agent string (UA)
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.47 Safari/537.36');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		$last_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
		return $last_url;
	}
}

if(! function_exists('request_status')){

	/**
	 * @param $url
	 * @return int|mixed
	 */
	function request_status($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($ch);
		curl_close($ch);
		if($result !== FALSE)
			return true;
		else
			return false;
	}
}

if(! function_exists('image_exists')){
	function image_exists($url){
		$im = @imagecreatefromjpeg($url);
		if($im) {
			imagedestroy($im);
			return true;
		} else return false;

	}
}


if(! function_exists('output_json')){

	/**
	 * @param array $array
	 * @return string
	 */
	function output_json($array){
		return "[".json_encode($array)."]";
	}
}

if(! function_exists('str')){

    /**
     * Get Stringable object
     * @param string $string
     * @return Lite\Support\Stringable
     */
    function str($string){
        return Lite\Support\Str::of($string);
    }
}

if(! function_exists('collect')){

    /**
     * Create collection from array
     * @param array|object $array
     * @return Lite\Support\Collection
     */
    function collect($array){
        return new Lite\Support\Collection($array);
    }
}

if(! function_exists('view')){

	/**
	 * Render view
	 * @param string $view
	 * @param array $collection
	 * @throws \Lite\Exceptions\ViewException
	 */
    function view($view, $collection = null){
        $view = str_replace('.','/',$view);

        if(defined("VIEW_PATH"))
        	$path = path(VIEW_PATH . "/{$view}.php");
        else
            $path = app_path("app/views/{$view}.php");

        if(!file_exists($path))
            throw new Lite\Exceptions\ViewException('View Not Found');
        
       ob_start();

       if($collection)
         extract($collection);

        require_once $path;

       ob_flush();  
    }
}

if(! function_exists('asset')){
    /**
     * Make asset url
     * @param string $asset
     * @return string
     */
    function asset($asset){
        return "/assets/{$asset}";
    }
}

if(! function_exists('base_url')){
	/**
	 * Get Base URL
	 * @return string
	 */
	function base_url(){
		return sprintf(
			"%s://%s",
			isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
			$_SERVER['SERVER_NAME']);
	}
}

if(! function_exists('short_base_url')){
	/**
	 * Get Short Base URL
	 * @return string
	 */
	function short_base_url(){
		return sprintf(
			"%s",
			$_SERVER['SERVER_NAME']);
	}
}

if(!function_exists('search_json')){
	/**
	 * @param string $data
	 * @return mixed
	 */
	function search_json($data){
		$startAt = preg_quote('<script id="__NEXT_DATA__" type="application/json" crossorigin="anonymous">', '/');
		$endEnd = preg_quote('</script>', '/');
		preg_match("/$startAt(.*?)$endEnd/", $data, $matches);
		return $matches;
	}
}

if(! function_exists('response')){
	/**
	 * Generate response
	 * @param $resp string|mixed
	 * @param int $status
	 */
	function response($resp,$status = 200){
		//Get an array if response is instance of response
		if($resp instanceof Resource)
			$resp =$resp->toArray();
		elseif($resp instanceof Eloquent)
			$resp = $resp->attributesToArray();
		//Else if is data an array
		elseif(is_array($resp)){
			foreach($resp as &$value){
				if($value instanceof Resource)
					$value =$value->toArray();
				elseif($value instanceof Eloquent)
					$value = $value->attributesToArray();
			}
		}

		//Return response
		echo json_encode($resp);
		//Set response status code
		http_response_code((int)$status);
		//Die
		die();
	}
}

if(! function_exists('redirect')){
	/**
	 * Redirect to specific page
	 * @param string $url
	 * @param int $status
	 */
	function redirect($url,$status = 200){
		header("Location: /".$url);
		die();
	}
}



if (! function_exists('blank')) {
	/**
	 * Determine if the given value is "blank".
	 *
	 * @param  mixed  $value
	 * @return bool
	 */
	function blank($value)
	{
		if (is_null($value)) {
			return true;
		}

		if (is_string($value)) {
			return trim($value) === '';
		}

		if (is_numeric($value) || is_bool($value)) {
			return false;
		}

		if ($value instanceof Countable) {
			return count($value) === 0;
		}

		return empty($value);
	}
}

if (! function_exists('class_basename')) {
	/**
	 * Get the class "basename" of the given object / class.
	 *
	 * @param  string|object  $class
	 * @return string
	 */
	function class_basename($class)
	{
		$class = is_object($class) ? get_class($class) : $class;

		return basename(str_replace('\\', '/', $class));
	}
}

if (! function_exists('class_uses_recursive')) {
	/**
	 * Returns all traits used by a class, its parent classes and trait of their traits.
	 *
	 * @param  object|string  $class
	 * @return array
	 */
	function class_uses_recursive($class)
	{
		if (is_object($class)) {
			$class = get_class($class);
		}

		$results = [];

		foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
			$results += trait_uses_recursive($class);
		}

		return array_unique($results);
	}
}

if (! function_exists('data_fill')) {
	/**
	 * Fill in data where it's missing.
	 *
	 * @param  mixed  $target
	 * @param  string|array  $key
	 * @param  mixed  $value
	 * @return mixed
	 */
	function data_fill(&$target, $key, $value)
	{
		return data_set($target, $key, $value, false);
	}
}

if (! function_exists('data_get')) {
	/**
	 * Get an item from an array or object using "dot" notation.
	 *
	 * @param  mixed  $target
	 * @param  string|array|int|null  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	function data_get($target, $key, $default = null)
	{
		if (is_null($key)) {
			return $target;
		}

		$key = is_array($key) ? $key : explode('.', $key);

		while (! is_null($segment = array_shift($key))) {
			if ($segment === '*') {
				if ($target instanceof Collection) {
					$target = $target->all();
				} elseif (! is_array($target)) {
					return value($default);
				}

				$result = [];

				foreach ($target as $item) {
					$result[] = data_get($item, $key);
				}

				return in_array('*', $key) ? Arr::collapse($result) : $result;
			}

			if (Arr::accessible($target) && Arr::exists($target, $segment)) {
				$target = $target[$segment];
			} elseif (is_object($target) && isset($target->{$segment})) {
				$target = $target->{$segment};
			} else {
				return value($default);
			}
		}

		return $target;
	}
}

if (! function_exists('data_set')) {
	/**
	 * Set an item on an array or object using dot notation.
	 *
	 * @param  mixed  $target
	 * @param  string|array  $key
	 * @param  mixed  $value
	 * @param  bool  $overwrite
	 * @return mixed
	 */
	function data_set(&$target, $key, $value, $overwrite = true)
	{
		$segments = is_array($key) ? $key : explode('.', $key);

		if (($segment = array_shift($segments)) === '*') {
			if (! Arr::accessible($target)) {
				$target = [];
			}

			if ($segments) {
				foreach ($target as &$inner) {
					data_set($inner, $segments, $value, $overwrite);
				}
			} elseif ($overwrite) {
				foreach ($target as &$inner) {
					$inner = $value;
				}
			}
		} elseif (Arr::accessible($target)) {
			if ($segments) {
				if (! Arr::exists($target, $segment)) {
					$target[$segment] = [];
				}

				data_set($target[$segment], $segments, $value, $overwrite);
			} elseif ($overwrite || ! Arr::exists($target, $segment)) {
				$target[$segment] = $value;
			}
		} elseif (is_object($target)) {
			if ($segments) {
				if (! isset($target->{$segment})) {
					$target->{$segment} = [];
				}

				data_set($target->{$segment}, $segments, $value, $overwrite);
			} elseif ($overwrite || ! isset($target->{$segment})) {
				$target->{$segment} = $value;
			}
		} else {
			$target = [];

			if ($segments) {
				data_set($target[$segment], $segments, $value, $overwrite);
			} elseif ($overwrite) {
				$target[$segment] = $value;
			}
		}

		return $target;
	}
}

if (! function_exists('filled')) {
	/**
	 * Determine if a value is "filled".
	 *
	 * @param  mixed  $value
	 * @return bool
	 */
	function filled($value)
	{
		return ! blank($value);
	}
}

if (! function_exists('object_get')) {
	/**
	 * Get an item from an object using "dot" notation.
	 *
	 * @param  object  $object
	 * @param  string|null  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	function object_get($object, $key, $default = null)
	{
		if (is_null($key) || trim($key) == '') {
			return $object;
		}

		foreach (explode('.', $key) as $segment) {
			if (! is_object($object) || ! isset($object->{$segment})) {
				return value($default);
			}

			$object = $object->{$segment};
		}

		return $object;
	}
}

if (! function_exists('preg_replace_array')) {
	/**
	 * Replace a given pattern with each value in the array in sequentially.
	 *
	 * @param  string  $pattern
	 * @param  array  $replacements
	 * @param  string  $subject
	 * @return string
	 */
	function preg_replace_array($pattern, array $replacements, $subject)
	{
		return preg_replace_callback($pattern, function () use (&$replacements) {
			foreach ($replacements as $key => $value) {
				return array_shift($replacements);
			}
		}, $subject);
	}
}

if (! function_exists('retry')) {
	/**
	 * Retry an operation a given number of times.
	 *
	 * @param  int  $times
	 * @param  callable  $callback
	 * @param  int  $sleep
	 * @param  callable|null  $when
	 * @return mixed
	 *
	 * @throws \Exception
	 */
	function retry($times, callable $callback, $sleep = 0, $when = null)
	{
		$attempts = 0;

		beginning:
		$attempts++;
		$times--;

		try {
			return $callback($attempts);
		} catch (Exception $e) {
			if ($times < 1 || ($when && ! $when($e))) {
				throw $e;
			}

			if ($sleep) {
				usleep($sleep * 1000);
			}

			goto beginning;
		}
	}
}

if (! function_exists('throw_if')) {
	/**
	 * Throw the given exception if the given condition is true.
	 *
	 * @param  mixed  $condition
	 * @param  \Throwable|string  $exception
	 * @param  array  ...$parameters
	 * @return mixed
	 *
	 * @throws \Throwable
	 */
	function throw_if($condition, $exception, ...$parameters)
	{
		if ($condition) {
			throw (is_string($exception) ? new $exception(...$parameters) : $exception);
		}

		return $condition;
	}
}

if (! function_exists('throw_unless')) {
	/**
	 * Throw the given exception unless the given condition is true.
	 *
	 * @param  mixed  $condition
	 * @param  \Throwable|string  $exception
	 * @param  array  ...$parameters
	 * @return mixed
	 *
	 * @throws \Throwable
	 */
	function throw_unless($condition, $exception, ...$parameters)
	{
		if (! $condition) {
			throw (is_string($exception) ? new $exception(...$parameters) : $exception);
		}

		return $condition;
	}
}

if (! function_exists('trait_uses_recursive')) {
	/**
	 * Returns all traits used by a trait and its traits.
	 *
	 * @param  string  $trait
	 * @return array
	 */
	function trait_uses_recursive($trait)
	{
		$traits = class_uses($trait);

		foreach ($traits as $trait) {
			$traits += trait_uses_recursive($trait);
		}

		return $traits;
	}
}

if (! function_exists('last')) {
	/**
	 * Get the last element from an array.
	 *
	 * @param  array  $array
	 * @return mixed
	 */
	function last($array)
	{
		return end($array);
	}
}

if (! function_exists('value')) {
	/**
	 * Return the default value of the given value.
	 *
	 * @param  mixed  $value
	 * @return mixed
	 */
	function value($value)
	{
		return $value instanceof Closure ? $value() : $value;
	}
}

if (! function_exists('with')) {
	/**
	 * Return the given value, optionally passed through the given callback.
	 *
	 * @param  mixed  $value
	 * @param  callable|null  $callback
	 * @return mixed
	 */
	function with($value, callable $callback = null)
	{
		return is_null($callback) ? $value : $callback($value);
	}
}

if(! function_exists('get_openssl_version_number')){

	/**
 * Parse OPENSSL_VERSION_NUMBER constant to
 * use in version_compare function
 * @param  boolean $patch_as_number        [description]
 * @param  [type]  $openssl_version_number [description]
 * @return [type]                          [description]
 */
function get_openssl_version_number($openssl_version_number=null, $patch_as_number=false) {
    if (is_null($openssl_version_number)) $openssl_version_number = OPENSSL_VERSION_NUMBER;
    $openssl_numeric_identifier = str_pad((string)dechex($openssl_version_number),8,'0',STR_PAD_LEFT);

    $openssl_version_parsed = array();
    $preg = '/(?<major>[[:xdigit:]])(?<minor>[[:xdigit:]][[:xdigit:]])(?<fix>[[:xdigit:]][[:xdigit:]])';
    $preg.= '(?<patch>[[:xdigit:]][[:xdigit:]])(?<type>[[:xdigit:]])/';
    preg_match_all($preg, $openssl_numeric_identifier, $openssl_version_parsed);
    $openssl_version = false;
    if (!empty($openssl_version_parsed)) {
        $alphabet = array(1=>'a',2=>'b',3=>'c',4=>'d',5=>'e',6=>'f',7=>'g',8=>'h',9=>'i',10=>'j',11=>'k',
                                       12=>'l',13=>'m',14=>'n',15=>'o',16=>'p',17=>'q',18=>'r',19=>'s',20=>'t',21=>'u',
                                       22=>'v',23=>'w',24=>'x',25=>'y',26=>'z');
        $openssl_version = intval($openssl_version_parsed['major'][0]).'.';
        $openssl_version.= intval($openssl_version_parsed['minor'][0]).'.';
        $openssl_version.= intval($openssl_version_parsed['fix'][0]);
        $patchlevel_dec = hexdec($openssl_version_parsed['patch'][0]);
        if (!$patch_as_number && array_key_exists($patchlevel_dec, $alphabet)) {
            $openssl_version.= $alphabet[$patchlevel_dec]; // ideal for text comparison
        }
        else {
            $openssl_version.= '.'.$patchlevel_dec; // ideal for version_compare
        }
    }
    return $openssl_version;
}
}

//WRAPPER HELPERS
if(! function_exists('search_input')){

    /**
     * @param string $body
     * @param string $input
     * @return string|null|void
     */
    function search_input($body,$input){
        $startAt = preg_quote("<input id=\"{$input}\" name=\"{$input}\" type=\"hidden\" value=\"", '/');
        $endEnd = preg_quote("\">", '/');
        preg_match("/$startAt(.*?)$endEnd/", $body, $matches);
        return $matches;
    }
}


if(! function_exists('make_id')){

    function make_id($length = 16){
        $text = '';
        $CHAR_SET = "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($CHAR_SET - 1));
            $text .= $CHAR_SET[$index];
        }
        return $text;
    }
}

if(! function_exists('is_valid_proxy')){

    function is_valid_proxy($proxy){
        if (!is_string($proxy) && !is_array($proxy)) {
            return false;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'http://www.tiktok.com',
                [
                    "verify" => SSL_ENABLED,
                    "timeout" => 10,
                    "proxy" => $proxy
                ]);
            $code = $res->getStatusCode();
        } catch (Exception $e) {
            return false;
        }

        return $code == 200;
    }
}

if(! function_exists('get_random_proxy')){

    function get_random_proxy(){
        $proxies = config('proxy');

        if(!count($proxies)) return null;

        return Arr::random($proxies);
    }
}

if(! function_exists('random_user_agent')){

    function random_user_agent(){
        $os = [
            'Macintosh; Intel Mac OS X 10_15_7',
            'Macintosh; Intel Mac OS X 10_15_5',
            'Macintosh; Intel Mac OS X 10_11_6',
            'Macintosh; Intel Mac OS X 10_6_6',
            'Macintosh; Intel Mac OS X 10_9_5',
            'Macintosh; Intel Mac OS X 10_10_5',
            'Macintosh; Intel Mac OS X 10_7_5',
            'Macintosh; Intel Mac OS X 10_11_3',
            'Macintosh; Intel Mac OS X 10_10_3',
            'Macintosh; Intel Mac OS X 10_6_8',
            'Macintosh; Intel Mac OS X 10_10_2',
            'Macintosh; Intel Mac OS X 10_10_3',
            'Macintosh; Intel Mac OS X 10_11_5',
            'Windows NT 10.0; Win64; x64',
            'Windows NT 10.0; WOW64',
            'Windows NT 10.0',
        ];

        $rnd_os = Arr::random($os);
        $chrome_version = floor(rand(0,3)) + 85;
        $chrome_sub_version = floor(rand(0,190)) + 4100;
        $chrome_sub_sub_version = floor(rand(0,50)) + 140;

        return "Mozilla/5.0 ($rnd_os) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$chrome_version}.{$chrome_sub_version}.{$chrome_sub_sub_version} Safari/537.36";
    }
}