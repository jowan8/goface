<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Dbcommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class WepayController extends Controller{

    /**
     *微信支付签名
     */
    public function wechat_pay( Request $request ){

        $inputs = $request->inputs;

        $uid = $request->uid;
        $appid = Config::get('constants.WX_APPID');
        $mch_id = Config::get('constants.MCH_ID');
        $key = Config::get('constants.WX_PAY_KEY');
        $notify_url = Config::get('constants.NOTIFY_URL');
        $body = "好律师名片支付";
        $price = 0.01;
        $total_fee = $price*100;
        //获取openid
        $db = new Dbcommon();
        $userInfo = $db->common_select('user',['id'=>$uid],['uopenid','is_lawyer']);
        if($userInfo->is_lawyer==1){
            jsonout(403,'你已经有了自己的名片');
        }else{
            $uopenid = $userInfo->uopenid;
        }
        $openid= $uopenid;
        $out_trade_no = date('YmdHis').rand(1000,9999);
        //判断有误订单
        $orderinfo = $db->common_select('order',['u_id'=>$uid,'order_type'=>1],['order_num','pay_status','order_type']);
        if(!empty($orderinfo)){
            if($orderinfo->pay_status==1&&$orderinfo->order_type==1){
                jsonout(200,'success',array('status'=>1,'order_num'=>$orderinfo->order_num));
            }else{
                $resUp = $db->common_update('order',['u_id'=>$uid,'order_type'=>1],['order_num'=>$out_trade_no]);
                if(!$resUp){
                    jsonout(500,'服务器错误');
                }
            }
        }else{
            //生成订单
            $insertData = array(
                'u_id'=>$uid,
                'order_num'=>$out_trade_no,
                'order_type'=>1,
                'pay_status'=>0,
                'pay_type'=>1,
                'pay_price'=>$price,
            );
            $res = $db->common_insert('order',$insertData);
            if($res==false){
                jsonout('500','支付失败');
            }
        }
        $weixinpay = new \WeixinPay();
        $return=$weixinpay->pay($appid,$openid,$mch_id,$key,$out_trade_no,$body,$total_fee,$notify_url);

       jsonout(200,'success',array('status'=>0,'order_num'=>$out_trade_no,'sign'=>$return));

    }
    /*
     *支付验签
     * */
    public function wechat_pay_query( Request $request ){
        $inputs = $request->inputs;

        $order_num=isset($inputs['order_num'])?$inputs['order_num']:'';
        if(empty($order_num)){
            jsonout(400,'订单有误');
        }
        $uid = $request->uid;
        $key = Config::get('constants.WX_PAY_KEY');
        $appid = Config::get('constants.WX_APPID');
        $mch_id = Config::get('constants.MCH_ID');
        $db = new Dbcommon();
        $orderInfo = $db->common_select('order',['order_num'=>$order_num],['id','u_id','order_type','pay_status','pay_price']);
        if(empty($orderInfo)){
            jsonout(400,'订单号有误');
        }
        if($uid!=$orderInfo->u_id){
            jsonout(400,'你不是本人');
        }
        $weixinpay = new \WeixinPay();
        $payInfo = $weixinpay->orderQuery($appid,$mch_id,$order_num,$key);
        if($payInfo['status']!=false){

            if( $orderInfo->pay_price*100!=$payInfo['data']['total_fee']){
                $insertRes = $db->common_insert('order_records',['order_id'=>$orderInfo->id,'pay_status'=>2,'reason'=>'价格不对']);
                jsonout(400,'金额有误');
            }
            if($appid!=Config::get('constants.WX_APPID')){
                $insertRes = $db->common_insert('order_records',['order_id'=>$orderInfo->id,'pay_status'=>2,'reason'=>'appid有误']);
                jsonout(400,'支付失败');
            }
            if($orderInfo->pay_status==1){
                jsonout(200,'success');
            }else{
                DB::beginTransaction();
                //修改订单支付状态
                $upRes = $db->common_update('order',['order_num'=>$order_num],['pay_status'=>1]);
                if(!$upRes){
                    DB::rollback();  //回滚
                    jsonout(500,'订单修改失败');
                }
                //插入记录
                $insertData = array(
                    'order_id'=>$orderInfo->id,
                    'pay_status'=>1,
                    'reason'=>'成功支付'
                );
                $insertRes = $db->common_insert('order_records',$insertData);
                if(!$insertRes){
                    DB::rollback();  //回滚
                    jsonout(500,'订单记录失败');
                }
                DB::commit();//提交
                jsonout(200,'success');
            }
        }else{
            $insertRes = $db->common_insert('order_records',['order_id'=>$orderInfo->id,'pay_status'=>2,'reason'=>'未付款']);
            jsonout(400,'未付款');
        }
    }
    /*
     * 异步通知地址
     */
    public function notify(){
        $postXml = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA']:''; //接收微信参数
        if(empty($postXml)){
            $postXml = file_get_contents("php://input");
        }
//        $postXml = file_get_contents('../app/wxtest/test.log');
        $weixinpay = new \WeixinPay();
        $getorder = $weixinpay->xmlToArray($postXml);
        $out_trade_no = $getorder['out_trade_no'];
        $db = new Dbcommon();
        $orderInfo = $db->common_select('order',['order_num'=>$out_trade_no],['id','u_id','order_type','pay_status','pay_price']);
        if(empty($orderInfo)){
            exit;
        }
        //成功返回给微信的数据
        $reply = "<xml>
					<return_code><![CDATA[SUCCESS]]></return_code>
					<return_msg><![CDATA[OK]]></return_msg>
				</xml>";
        $key = Config::get('constants.WX_PAY_KEY');
        $appid = Config::get('constants.WX_APPID');
        $mch_id = Config::get('constants.MCH_ID');
        $weixinpay = new \WeixinPay();
        $payInfo = $weixinpay->orderQuery($appid,$mch_id,$out_trade_no,$key);
        if($payInfo['status']!=false){

            if( $orderInfo->pay_price*100!=$payInfo['data']['total_fee']){
                $insertRes = $db->common_insert('order_records',['order_id'=>$orderInfo->id,'pay_status'=>2,'reason'=>'价格不对']);
                exit;
            }
            if($appid!=Config::get('constants.WX_APPID')){
                $insertRes = $db->common_insert('order_records',['order_id'=>$orderInfo->id,'pay_status'=>2,'reason'=>'appid有误']);
                exit;
            }
            if($orderInfo->pay_status==1){
                echo $reply;exit;
            }else{
                DB::beginTransaction();
                //修改订单支付状态
                $upRes = $db->common_update('order',['order_num'=>$out_trade_no],['pay_status'=>1]);
                if(!$upRes){
                    DB::rollback();  //回滚
                    exit;
                }
                //插入记录
                $insertData = array(
                    'order_id'=>$orderInfo->id,
                    'pay_status'=>1,
                    'reason'=>'成功支付'
                );
                $insertRes = $db->common_insert('order_records',$insertData);
                if(!$insertRes){
                    DB::rollback();  //回滚
                    exit;
                }
                DB::commit();//提交
                echo $reply;exit;

            }
        }else{
            $insertRes = $db->common_insert('order_records',['order_id'=>$orderInfo->id,'pay_status'=>2,'reason'=>'未付款']);
            exit;
        }


    }
}

