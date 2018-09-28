<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Dbcommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request){

        $inputs=$request->inputs;
        //检测版本号
        $code=isset($inputs['code'])?$inputs['code']:'';
//        $rawData=isset($inputs['rawData'])?$inputs['rawData']:'';
//        $signature=isset($inputs['signature'])?$inputs['signature']:'';
        $encryptedData=isset($inputs['encryptedData'])?$inputs['encryptedData']:'';
        $iv=isset($inputs['iv'])?$inputs['iv']:'';
        //验证数据
        $appid = Config::get('constants.WX_APPID');
        $openids = $this->get_openid($code);
        $openids = json_decode($openids,true);
        $sessionKey = isset($openids['session_key'])?$openids['session_key']:'';
        if(empty($code)||empty($encryptedData)||empty($iv)){
            jsonout(400,'wrong param');
        }
        if(empty($sessionKey)){
            jsonout(402,'get sessionKey failed');
        }
//        $openid = $openids['openid'];
        $skey = md5($sessionKey);
        $Obj = new \WxBizDataCrypt($appid, $sessionKey);
        $errCode = $Obj->decryptData($encryptedData, $iv,$data);
        if(empty($data)){
            exit;
            //jsonout(500,'解密数据有误');
        }
        if ($errCode == 0) {
            $userInfo = json_decode($data,true);
        } else {
            jsonout(402,$errCode);
        }
        if(empty($userInfo)){
            jsonout(500,'decrypted data failed');
        }
        $unionId = isset($userInfo['unionId'])?$userInfo['unionId']:'';
        //判断有无该用户
        $db = new Dbcommon();
        $is_data = $db->common_select('wechat_user',['openid'=>$userInfo['openId']],['id']);
        if(empty(json_decode(json_encode($is_data),true))){
            //添加数据库
            DB::beginTransaction();
            $insertDataUser = array(
                'openid'=>$userInfo['openId'],
                'unionid'=>$unionId,
            );
            $user_id = $db->insert_and_get_id('wechat_user',$insertDataUser);
            if($user_id<1){
                DB::rollback();  //回滚
                jsonout(500,'inner error');
            }
            $insertDataUserInfo = array(
                'user_id'=>$user_id,
                'gender'=>$userInfo['gender'],
                'nickname'=>$userInfo['nickName'],
                'avatarurl'=>$userInfo['avatarUrl'],
                'country'=>$userInfo['country'],
                'province'=>$userInfo['province'],
                'city'=>$userInfo['city'],
                'session_key'=>$sessionKey,
                'token'=>$skey,
            );
            $resUserInfo = $db->common_insert('user_info',$insertDataUserInfo);
            if(!$resUserInfo){
                DB::rollback();  //回滚
                jsonout(500,'inner error');
            }
            DB::commit();//提交
        }else{
            //修改数据库
            $user_id = $is_data->id;
            $upDataUserInfo = array(
                'gender'=>$userInfo['gender'],
                'nickname'=>$userInfo['nickName'],
                'avatarurl'=>$userInfo['avatarUrl'],
                'country'=>$userInfo['country'],
                'province'=>$userInfo['province'],
                'city'=>$userInfo['city'],
                'session_key'=>$sessionKey,
                'token'=>$skey,
            );
            $resUpUserInfo = $db->common_update('user_info',['user_id'=>$user_id],$upDataUserInfo);
            if(!$resUpUserInfo){
                jsonout(500,'inner error');
            }
        }

        $selectata = ['user_id','gender','nickname','avatarurl','country','province','city','session_key','token'];
        //查询该用户的用户信息
        $userinfo = $db->common_select('user_info',['user_id'=>$user_id],$selectata);
        jsonout(200,'success',$userinfo);
    }
    private function get_openid($code){
        $curl = new \CurlRequest();
        $curl->url = 'https://api.weixin.qq.com/sns/jscode2session';
        $appid = Config::get('constants.WX_APPID');
        $appsecret = Config::get('constants.WX_APPSECRET');
        $curl->data = [
            'appid'       =>$appid,
            'secret'    =>$appsecret,
            'js_code'      =>$code,
            'grant_type'     =>'authorization_code'
        ];

        $result = $curl->arrayGet();
        return $result;
    }
}
