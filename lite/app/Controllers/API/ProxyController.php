<?php


namespace App\Controllers\API;


use Lite\Http\Controller;
use Lite\Utility\Config;

/**
 * Class ProxyController
 * @package App\Controllers\API
 * @since 2.2.9
 */
class ProxyController extends Controller
{

    public function __construct()
    {
        $this->checkPermissions();
    }

    public function get(){
        $config = new Config('proxy');
        return response($config->getAttributes());
    }

    public function add(){
        $proxy = $_POST['proxy'] ?? null;

        if(!filter_var($proxy, FILTER_SANITIZE_STRING))
            return response(['error'=> 'The proxy is invalid.'],500);


        if(!is_valid_proxy($proxy))
            return response(['error'=> 'Proxy is not valid or active!'],500);

        try {

            $config = new Config('proxy');
            $proxies = $config->getAttributes();
            if (in_array($proxy, $proxies))
                return response(['error' => 'Proxy already exists in database.'], 500);

            array_push($proxies, $proxy);

            $config->replaceWith($proxies)->save();

            return response($proxies);

        }catch (\Exception $exception){
            return response(['error'=> $exception->getMessage()],500);
        }
    }

    public function delete(){
        $proxy = $_REQUEST['proxy'] ?? null;

        if(!filter_var($proxy, FILTER_SANITIZE_STRING))
            return response(['error'=> 'The proxy is invalid.'],500);

        try {

            $config = new Config('proxy');
            $proxies = $config->getAttributes();
            if (!in_array($proxy, $proxies))
                return response(['error' => 'Proxy does not exists in database.'], 500);

            array_splice($proxies, array_search($proxy, $proxies ), 1);

            $config->replaceWith($proxies)->save();

            return response($proxies);

        }catch (\Exception $exception){
            return response(['error'=> $exception->getMessage()],500);
        }
    }
}