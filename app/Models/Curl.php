<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
//公共的curl请求模型
class Curl extends Model
{

    /*
     * 调用 Post 并且用json数据流方式传值 类型API 接口
     * @param $data 请求数据 array()
     * @return json
     */
    public function jsonPostData($url,$data){
        $data_string = json_encode($data);
        $apiDomain = Config::get('constants.API_DOMAIN');
        $curl = new \CurlRequest();
        $curl->url = $apiDomain.'/api/'.$url;
        $curl->data = $data_string;
        $result = $curl->jsonPost();
        return $result;
    }

    /*
     * 调用 Post 并且用数组数据方式传值 类型API 接口
     * @param $data 请求数据 array()
     * @return json
     */
    public function arrayPostData($url,$data){
        $apiDomain = Config::get('constants.API_DOMAIN');
        $curl = new \CurlRequest();
        $curl->url = $apiDomain.'/api/'.$url;
        $curl->data = $data;
        $result = $curl->arrayPost();
        return $result;
    }

    /*
    * 调用 Post 并且用数组数据方式传值 类型API 接口
    * @param $data 请求数据 array()
    * @return json
    */
    public function arrayGetData($url,$data){
        $apiDomain = Config::get('constants.API_DOMAIN');
        $curl = new \CurlRequest();
        $curl->url = $apiDomain.'/api/'.$url;
        $curl->data = $data;
        $result = $curl->arrayGet();
        return $result;
    }



}