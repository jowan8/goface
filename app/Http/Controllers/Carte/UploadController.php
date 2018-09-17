<?php
namespace App\Http\Controllers\Carte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OSS\OssClient;
use Illuminate\Support\Facades\Config;

class UploadController extends Controller{

    /**
     * 上传文件
    */
    public function upload_file( Request $request ){
        require_once '../vendor/aliyuncs/oss-sdk-php/autoload.php';
        $inputs = $request->inputs;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }
        $myfile = $_FILES['myfile'];

        //时间/随机数作为文件
        $object = date('Y-m-d',time()).'/'.time().rand(1000,9999).substr($myfile['name'], strrpos($myfile['name'], '.'));
        $tmp_name = $myfile['tmp_name'];

        $accessKeyId = Config::get('constants.OSS_ACCESSKEYId');
        $accessKeySecret = Config::get('constants.OSS_ACCESSKEYSECRET');
        $endpoint = Config::get('constants.OSS_ENDPOINT');
        $bucket= Config::get('constants.OSS_BUCKETNAME');
        $oss_endpoint_front= Config::get('constants.OSS_ENDPOINT_FRONT');

        $content = file_get_contents($tmp_name);
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $uplodeinfo = $ossClient->putObject($bucket, $object, $content);
            if(empty($uplodeinfo)){
                jsonout(400,'文件上传错误');
            }
            if(empty($uplodeinfo['info'])){
                jsonout(400,'文件上传错误');
            }

            jsonout(200,'success',array('file_url'=>$oss_endpoint_front.'.'.$endpoint.'/'.$object));
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }



}

