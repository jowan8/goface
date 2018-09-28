<?php
/**
 * Created by PhpStorm.
 * User: zhen
 * Date: 2018/8/6
 * Time: 10:48
 */
namespace App\Http\Controllers\Share;

use App\Http\Controllers\Controller;
use App\Models\Curl;
use App\Models\Dbcommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use OSS\OssClient;

class ShareController extends Controller{

    /**
     * 获取用户小程序码
     * return json
    */
    public function share_info( Request $request ){
        $inputs = $request->inputs;



        if( !empty($inputs['user_id']) ){
            $u_id = isset($inputs['user_id'])?$inputs['user_id']:'';
        } else {
            $u_id = $request->uid;
        }

        if( empty($u_id) || !is_numeric($u_id) ){
            jsonout( 400,'参数错误' );
        }

        $db = new Dbcommon();
        $law_photo= $db->common_select('lawyer_info',['u_id'=>$u_id],['image_photo','law_qrcode']);
//        if( !empty($law_photo->law_qrcode) ){
//            jsonout( 200,'success',['file_url'=>$law_photo->law_qrcode] );
//        }

        $appid = Config::get('constants.WX_APPID');
        $appsecret = Config::get('constants.WX_APPSECRET');
        $send  = array('path' =>'pages/lawyerCard/lawyerCard?user_id='.$u_id, 'width'=>'200');//传给微信的参数
        $avatarUrl = $law_photo->image_photo;//用户头像url
//      $avatarUrl = 'https://ybf-dev.oss-cn-hangzhou.aliyuncs.com/2018-09-03/15359584804456.jpg';//用户头像url

//请求微信，获取小程序二维码
        $resWxQrCode = $this->getWxQrcode($send,$appid,$appsecret);

        //把图片变为正方形
        $square_img = $this->square($avatarUrl);
//        var_dump('data:image/png;base64,'.base64_encode($square_img));die;

//用户头像图片变圆形
//        $avatar = file_get_contents($square_img);

        $logo   = $this->yuanImg($square_img);//返回的是图片数据流

//二维码与头像结合
        $sharePic = $this->qrcodeWithLogo($resWxQrCode,$logo);

        $file = 'data:image/png;base64,'.base64_encode($sharePic);//二进制流文件
        $img = $this->binary_to_file($file);//把二进制流文件转为图片保存
        $qrcode = $db->common_update('lawyer_info',['u_id'=>$u_id],['law_qrcode'=>$img['file_url']]);

        jsonout( 200,'success',$img );
        //  'https://ybf-dev.oss-cn-hangzhou.aliyuncs.com/QRcode/2018-09-04/15360518916893.png' 小程序码

    }

    /** 二进制流生成文件
     * $_POST 无法解释二进制流，需要用到 $GLOBALS['HTTP_RAW_POST_DATA'] 或 php://input
     * $GLOBALS['HTTP_RAW_POST_DATA'] 和 php://input 都不能用于 enctype=multipart/form-data
     * @param    String  $file   要生成的文件路径
     * @return   boolean
     */
    private function binary_to_file($file){
        require_once '../vendor/aliyuncs/oss-sdk-php/autoload.php';

        //时间/随机数作为文件
        $object = 'QRcode/'.date('Y-m-d',time()).'/'.time().rand(1000,9999).'.png';
        $accessKeyId = Config::get('constants.OSS_ACCESSKEYId');
        $accessKeySecret = Config::get('constants.OSS_ACCESSKEYSECRET');
        $endpoint = Config::get('constants.OSS_ENDPOINT');
        $bucket= Config::get('constants.OSS_BUCKETNAME');
        $oss_endpoint_front= Config::get('constants.OSS_ENDPOINT_FRONT');

        $content = file_get_contents($file);
        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $uplodeinfo = $ossClient->putObject($bucket, $object, $content);
            if(empty($uplodeinfo)){
                jsonout(400,'文件上传错误');
            }
            if(empty($uplodeinfo['info'])){
                jsonout(400,'文件上传错误');
            }

            return array('file_url'=>$oss_endpoint_front.'.'.$endpoint.'/'.$object);
        } catch (OssException $e) {
            print $e->getMessage();
        }
    }




    /**
     * curl方法
     * @param $url 请求url
     * @param $data 传送数据，有数据时使用post传递
     * @param type 为2时,设置json传递
     */
    private function curlRequest($url,$data = null , $type = 1){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            if($type == 2){
                curl_setopt($curl, CURLOPT_HTTPHEADER,
                    array('Content-Type: application/json','Content-Length: ' . strlen($data)));
            }
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /**
     * 请求微信服务器,生成二维码
     * @param $data array('scene'=>$setid, 'path' =>'pages/question/question', 'width'=>'100');
     */
    private function getWxQrcode($data,$appid,$appsecret){
        //get access_token
        $wxTokenUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        $access_token = $this->curlRequest($wxTokenUrl);
        $access_token = json_decode($access_token,true);
//        var_dump($access_token);die;
        if( !isset($access_token['access_token']) )
            var_dump(['code'=>2004,'msg'=>'请求微信服务器access_token失败']);

        //get qrcode 微信B接口
//        $wxQrcodeUrl = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$access_token['access_token'];
        $wxQrcodeUrl = "https://api.weixin.qq.com/wxa/getwxacode?access_token=".$access_token['access_token'];
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        return $this->curlRequest($wxQrcodeUrl,$data);
    }

    /**
     * 在二维码的中间区域镶嵌图片
     * @param $QR 二维码数据流。比如file_get_contents(imageurl)返回的东东,或者微信给返回的东东
     * @param $logo 中间显示图片的数据流。比如file_get_contents(imageurl)返回的东东
     * @return  返回图片数据流
     */
    private function qrcodeWithLogo($QR,$logo){
        $QR   = imagecreatefromstring ($QR);
        $logo = imagecreatefromstring ($logo);
        $QR_width    = imagesx ( $QR );//二维码图片宽度
        $QR_height   = imagesy ( $QR );//二维码图片高度
        $logo_width  = imagesx ( $logo );//logo图片宽度
        $logo_height = imagesy ( $logo );//logo图片高度

        $logo_qr_width  = $QR_width / 2.2;//组合之后logo的宽度(占二维码的1/2.2)
        $scale  = $logo_width / $logo_qr_width;//logo的宽度缩放比(本身宽度/组合后的宽度)
        $logo_qr_height = $logo_height / $scale;//组合之后logo的高度
        $from_width = ($QR_width - $logo_qr_width) / 2;//组合之后logo左上角所在坐标点
        /**
         * 重新组合图片并调整大小
         * imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
         */
        imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
        /**
         * 如果想要直接输出图片，应该先设header。header("Content-Type: image/png; charset=utf-8");
         * 并且去掉缓存区函数
         */
        //获取输出缓存，否则imagepng会把图片输出到浏览器
        ob_start();
        imagepng ( $QR );
        imagedestroy($QR);
        imagedestroy($logo);
        $contents =  ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    /**
     * 图片设置相同的宽和高
     * @param string $image
     * @return number
     */
    static function square($image) {

        // 读取原图
        $sourceInfo = getimagesize($image);
        $sourceW = $sourceInfo[0]; // 取得图片的宽
        $sourceH = $sourceInfo[1]; // 取得图片的高

        // 设置原图和新图
        $sourceMin = min($sourceW,$sourceH);
        $sourceIm = imagecreatefrompng($image);
        $newIm = imagecreatetruecolor($sourceMin, $sourceMin);

        // 设定图像的混色模式
        imagealphablending($newIm, false);

        // 拷贝原图到新图
        $posX = $posY = 0;
        if($sourceW > $sourceH) {
            $posX = floor(($sourceH-$sourceW)/2);
        } else {
            $posY = floor(($sourceW-$sourceH)/2);
        }
        imagecopy($newIm, $sourceIm, $posX, $posY, 0, 0, $sourceW, $sourceH);

        // 生成图片替换原图
        @unlink($image);
        ob_start();
        imagepng ( $newIm);
        $contents =  ob_get_contents();
        ob_end_clean();
        return $contents;
//        return imagepng($newIm, $image);
    }


    /**
     * 剪切图片为圆形
     * @param  $picture 图片数据流 比如file_get_contents(imageurl)返回的东东
     * @return 图片数据流
     */
    private function yuanImg($picture) {
        $src_img = imagecreatefromstring($picture);
        $w   = imagesx($src_img);
        $h   = imagesy($src_img);
        $y_x = ($w-$h)/2;
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2; //圆半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }
        /**
         * 如果想要直接输出图片，应该先设header。header("Content-Type: image/png; charset=utf-8");
         * 并且去掉缓存区函数
         */
        //获取输出缓存，否则imagepng会把图片输出到浏览器
        ob_start();
        imagepng ( $img );
        imagedestroy($img);
        $contents =  ob_get_contents();
        ob_end_clean();
        return $contents;
    }



}