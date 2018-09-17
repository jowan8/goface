<?php
/**
 * Created by PhpStorm.
 * User: 17621
 * Date: 2018/8/22
 * Time: 16:00
 */
namespace App\Http\Controllers\Carte;

use App\Http\Controllers\Controller;
use App\Models\Dbcommon;
use App\Models\GetQrcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Curl;
use OSS\OssClient;

class CarteController extends Controller{

    /**
     * 默认用户
    */
    public function is_userid( Request $request ){
        $inputs = $request->inputs;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }
        $user_id = ['user_id'=>1];

        jsonout( 200,'success',$user_id );
    }

    /**
     * 添加名片信息
    */
    public function add_info( Request $request ){
        $inputs = $request->inputs;
        $u_id = $request->uid;
        $lawyer_name = isset($inputs['lawyer_name'])?$inputs['lawyer_name']:'';
        $phone = isset($inputs['phone'])?$inputs['phone']:'';
        $image_photo = isset($inputs['image_photo'])?$inputs['image_photo']:'';
        $wechat_2d_code = isset($inputs['wechat_2d_code'])?$inputs['wechat_2d_code']:'';
        $law_office_name = isset($inputs['law_office_name'])?$inputs['law_office_name']:'';
        $law_office_address = isset($inputs['law_office_address'])?$inputs['law_office_address']:'';
        $law_office_longitude = isset($inputs['law_office_longitude'])?$inputs['law_office_longitude']:'';
        $law_office_latitude = isset($inputs['law_office_latitude'])?$inputs['law_office_latitude']:'';
        $speciality_ids = isset($inputs['speciality_ids'])?$inputs['speciality_ids']:'';
        $take_office = isset($inputs['take_office'])?$inputs['take_office']:'';
        $synopsis = isset($inputs['synopsis'])?$inputs['synopsis']:'';
        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        if( empty($lawyer_name)||empty($phone)||empty($image_photo)||empty($wechat_2d_code)||empty($law_office_name)||empty($law_office_address)||empty($law_office_longitude)||empty($law_office_latitude)||empty($speciality_ids)||empty($take_office)||empty($synopsis) ){
            jsonout( 400,'参数不能为空' );
        }

        if (!preg_match("/^[\x{4e00}-\x{9fa5}]{2,6}+$/u",trim($lawyer_name))){
            jsonout( 400,'姓名必需为汉字，不能超过6个字' );
        }

        $this->is_param( $phone,$speciality_ids,$take_office,trim($law_office_name),$synopsis );

        $db = new Dbcommon();

        /*$order = $db->common_select('order',['u_id'=>$u_id],['order_type','pay_status']);
        if( $order->order_type!=1 && $order->pay_status!=1 ){ //是否支付
            jsonout( 400,'您还未付款' );
        }*/

        $info = $db->common_select('lawyer_info',['u_id'=>$u_id],'id');
        if( $info ){
            jsonout( 400,'已生成过名片' );
        }

        $getQrcode =  new GetQrcode();
        $qrcode = $getQrcode->get_Qrcodeimg($u_id,$image_photo);

        $data = [
            'u_id' => $u_id,
            'lawyer_name' => $lawyer_name,
            'phone' => $phone,
            'image_photo' => $image_photo,
            'wechat_2d_code' => $wechat_2d_code,
            'law_qrcode' =>$qrcode['file_url'],
            'law_office_name' => $law_office_name,
            'law_office_address' => $law_office_address,
            'law_office_longitude' => $law_office_longitude,
            'visitor_num' => 0,
            'fabulous_num' => 0,
            'law_office_latitude' => $law_office_latitude,
            'speciality_ids' => $speciality_ids,
            'take_office' => $take_office,
            'synopsis' => $synopsis
        ];

        DB::beginTransaction();
        $result = $db->common_insert('lawyer_info',$data);
        $is_lawyer = $db->common_update('user',['id'=>$u_id],['is_lawyer'=>1]);
        if( $result&&$is_lawyer ){
            DB::commit();
            jsonout( 200,'success' );
        }else{
            DB::rollback();
            jsonout( 500,'失败' );
        }
    }

    /**
     * 名片信息展示
    */
    public function info_show( Request $request ){
        $inputs = $request->inputs;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        $u_id = $this->is_token( $request,$inputs );
        $db = new Dbcommon();
        $select = ['u_id as user_id','lawyer_name','phone','image_photo','wechat_2d_code','law_qrcode','law_office_longitude','law_office_latitude','law_office_name','law_office_address','visitor_num','fabulous_num','speciality_ids','take_office','synopsis'];
         $lawyer_info = $db->common_select('lawyer_info',['u_id'=>$u_id],$select);
//        var_dump($lawyer_info);die;
        if( $lawyer_info ){
            $avatarurl = $this->is_access($db,$u_id); //是否有访客头像
            $field_id = explode(",",$lawyer_info->speciality_ids);//擅长领域id数组
//            $field = $db->wherein_selects('lawyer_field',[],'id',$field_id,['id as field_id','field_name']);//擅长领域数组
            $field_array = [];
            $field_num = count($field_id);
            for( $i=0;$i<$field_num;$i++ ){//擅长领域数组
                $field_array[] = $db->common_select('lawyer_field',['id'=>$field_id[$i]],['id as field_id','field_name']);
            }
            $lawyer_info->speciality_ids = $field_array;
            $lawyer_info->take_office = explode(',',$lawyer_info->take_office);
            $lawyer_info->avatarurl = $avatarurl;
            jsonout( 200,'success',$lawyer_info );
        } else {
            jsonout( 500,'请求失败' );
        }
    }

    /**
     * 修改名片信息
    */
    public function update_info( Request $request ){
        $inputs = $request->inputs;
        $u_id = $request->uid;
        $phone = isset($inputs['phone'])?$inputs['phone']:'';
        $image_photo = isset($inputs['image_photo'])?$inputs['image_photo']:'';
        $wechat_2d_code = isset($inputs['wechat_2d_code'])?$inputs['wechat_2d_code']:'';
        $law_office_name = isset($inputs['law_office_name'])?$inputs['law_office_name']:'';
        $law_office_address = isset($inputs['law_office_address'])?$inputs['law_office_address']:'';
        $law_office_longitude = isset($inputs['law_office_longitude'])?$inputs['law_office_longitude']:'';
        $law_office_latitude = isset($inputs['law_office_latitude'])?$inputs['law_office_latitude']:'';
        $speciality_ids = isset($inputs['speciality_ids'])?$inputs['speciality_ids']:'';
        $take_office = isset($inputs['take_office'])?$inputs['take_office']:'';
        $synopsis = isset($inputs['synopsis'])?$inputs['synopsis']:'';

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        $db = new Dbcommon();
        $select = ['phone','image_photo','wechat_2d_code','law_qrcode','law_office_longitude','law_office_latitude','law_office_name','law_office_address','speciality_ids','take_office','synopsis'];
        $info = $db->common_select('lawyer_info',['u_id'=>$u_id],$select);

        if( !$info || empty($info) ){
            jsonout( 500,'请求失败' );
        }

        if( !empty($image_photo) ){
            $getQrcode =  new GetQrcode();
            $qrcode = $getQrcode->get_Qrcodeimg($u_id,$image_photo);
        }

        $_phone = empty($phone)?$info->phone:$phone;
        $_image_photo = empty($image_photo)?$info->image_photo:$image_photo;
        $_law_qrcode = empty($qrcode['file_url'])?$info->law_qrcode:$qrcode['file_url'];
        $_wechat_2d_code = empty($wechat_2d_code)?$info->wechat_2d_code:$wechat_2d_code;
        $_law_office_name = empty($law_office_name)?$info->law_office_name:$law_office_name;
        $_law_office_address = empty($law_office_address)?$info->law_office_address:$law_office_address;
        $_law_office_longitude = empty($law_office_longitude)?$info->law_office_longitude:$law_office_longitude;
        $_law_office_latitude = empty($law_office_latitude)?$info->law_office_latitude:$law_office_latitude;
        $_speciality_ids = empty($speciality_ids)?$info->speciality_ids:$speciality_ids;
        $_take_office = empty($take_office)?$info->take_office:$take_office;
        $_synopsis = empty($synopsis)?$info->synopsis:$synopsis;

        $this->is_param( $_phone,$_speciality_ids,$_take_office,trim($_law_office_name),$_synopsis );

        $data = [
            'phone' => $_phone,
            'image_photo' => $_image_photo,
            'wechat_2d_code' => $_wechat_2d_code,
            'law_qrcode' => $_law_qrcode,
            'law_office_name' => $_law_office_name,
            'law_office_address' => $_law_office_address,
            'law_office_longitude' => $_law_office_longitude,
            'law_office_latitude' => $_law_office_latitude,
            'speciality_ids' => $_speciality_ids,
            'take_office' => $_take_office,
            'synopsis' => $_synopsis
        ];
        $lawyer_info = $db->common_update('lawyer_info',['u_id'=>$u_id],$data);
        if( $lawyer_info ){
            jsonout( 200,'success' );
        }else{
            jsonout( 500,'失败' );
        }
    }

    /**
     * 擅长领域列表
    */
    public function field_list( Request $request ){
        $inputs = $request->inputs;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        $db = new Dbcommon();
        $field = $db->common_selects('lawyer_field',[],['id as field_id','field_name']);
        if( $field ){
            jsonout( 200,'success',$field );
        }else{
            jsonout( 500,'失败' );
        }
    }

    /**
     * 我的页面
    */
    public function my_lawyer( Request $request ){
        $inputs = $request->inputs;
        $u_id = $request->uid;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        $db = new Dbcommon();
        $lawyer = $db->common_select('lawyer_info',['u_id'=>$u_id],['lawyer_name','image_photo']);

        if( $lawyer ){
            jsonout( 200,'success',$lawyer );
        }else{
            jsonout( 500,'失败' );
        }

    }

    /**
     * 是否是律师
    */
    public function is_lawyer( Request $request ){
        $inputs = $request->inputs;
        $u_id = $request->uid;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        $db = new Dbcommon();
        $lawyer = $db->common_select('user',['id'=>$u_id],'is_lawyer');
        if( $lawyer ){
            jsonout( 200,'success',$lawyer );
        }else{
            jsonout( 500,'失败' );
        }
    }

    /**
     * 点赞功能
    */
    public function add_praise( Request $request ){
        $inputs = $request->inputs;
        $u_id = isset($inputs['user_id'])?$inputs['user_id']:'';
        $fabulous_u_id = $request->uid;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        if( $u_id==''||!is_numeric($u_id) ){
            jsonout( 400,'参数错误' );
        }

        $db = new Dbcommon();

//        if( $u_id==$fabulous_u_id ){
//            jsonout( 400,'不能给自己点赞' );
//        }

        $where = ['u_id'=>$u_id,'fabulous_u_id'=>$fabulous_u_id];
        $fabulous_num = $db->common_select('fabulous',$where,['date_num','updated_at']);
        if( $fabulous_num ){  //是否有点赞记录
            $oldtime = date('Y-m-d',strtotime($fabulous_num->updated_at)); //修改时间
            $nowtime = date('Y-m-d',time()); //当天时间
            if( $fabulous_num->date_num<3 && $oldtime == $nowtime ){ //一天是否点赞超过三次
                $this->add_fabulous_num($where,$u_id);
            }else if( $oldtime!=$nowtime ){ //当天是否点赞
                $this->add_fabulous_num($where,$u_id);
            }
            $praise = $db->common_select('lawyer_info',['u_id'=>$u_id],'fabulous_num');//如果没点赞则查出点赞数量
            jsonout( 200,'success',$praise,0 );
        }
        DB::beginTransaction();
        $data = [
            'u_id' =>$u_id,
            'fabulous_u_id' => $fabulous_u_id,
            'date_num'   =>1
        ];
        $result = $db->common_insert('fabulous',$data);
        $law_info = $db->common_increase('lawyer_info',['u_id'=>$u_id],'fabulous_num' );
        if( $result && $law_info ){
            DB::commit();
            $praise = $db->common_select('lawyer_info',['u_id'=>$u_id],'fabulous_num');
            jsonout( 200,'success',$praise,1 );
        } else {
            DB::rollback();
            jsonout( 500 ,'请求失败');

        }

    }

    /**
     * 添加点赞次数
     * @param  $where   array   [ 条件 ]
     * @param  $u_id    in      [ 用户id ]
    */
    private function add_fabulous_num( $where,$u_id ){
        $db = new Dbcommon();
        DB::beginTransaction();
        $fabulous = $db->common_increase('fabulous',$where,'date_num');
        $law_info = $db->common_increase('lawyer_info',['u_id'=>$u_id],'fabulous_num' );
        if( $fabulous && $law_info ){
            DB::commit();
            $praise = $db->common_select('lawyer_info',['u_id'=>$u_id],'fabulous_num');
            jsonout( 200,'success',$praise,1 );
        }else{
            DB::rollback();
            jsonout( 500 ,'请求失败');
        }
    }

    /**
     * 取消点赞功能
     */
    public function less_praise( Request $request ){
        $inputs = $request->inputs;
        $u_id = isset($inputs['user_id'])?$inputs['user_id']:'';

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }
        if( $u_id==''||!is_numeric($u_id) ){
            jsonout( 400,'参数错误' );
        }

        $db = new Dbcommon();
        $fabulous_num = $db->common_select('lawyer_info',['u_id'=>$u_id],'fabulous_num');

        if( $fabulous_num->fabulous_num<=0 ){
            jsonout( 400,'未点赞' );
        }

        $result = $db->common_decrease('lawyer_info',['u_id'=>$u_id],'fabulous_num');

        if( $result ){
            $praise = $db->common_select('lawyer_info',['u_id'=>$u_id],'fabulous_num');
            jsonout( 200,'success',$praise );
        }else{
            jsonout( 500,'失败' );
        }

    }

    /**
     * 访客功能
     */
    public function access( Request $request ){
        $inputs = $request->inputs;
        $u_id = isset($inputs['user_id'])?$inputs['user_id']:'';
        $visitor_u_id = $request->uid;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误' );
        }
        if( $u_id=='' || !is_numeric($u_id) ){
            jsonout( 400,'参数错误' );
        }

        $db = new Dbcommon();
        if( $u_id==$visitor_u_id ){ //判断是否是本人访问
            $avatarurl = $this->is_access($db,$u_id); //是否有访客头像
            $access = $db->common_select('lawyer_info',['u_id'=>$u_id],'visitor_num');
            jsonout( 200,'success',$access );

        } else if( $visitor_u_id!='' ){
            if( !is_numeric($visitor_u_id) ){
                jsonout( 400,'参数错误' );
            }

            $is_visitor = $db->common_select('visitor',['u_id'=>$u_id,'visitor_u_id'=>$visitor_u_id],'visitor_u_id'); //查看是否已有访问记录
            if( !$is_visitor ){
                $data = [
                    'u_id' => $u_id,
                    'visitor_u_id' => $visitor_u_id
                ];
                $visitor = $db->common_insert('visitor',$data);
                if( !$visitor ){
                    jsonout( 500,'请求失败' );
                }
            }
            $avatarurl = $this->is_access($db,$u_id);//是否有访客头像
        } else {
            $avatarurl = $this->is_access($db,$u_id);//是否有访客头像
        }

        $result = $db->common_increase('lawyer_info',['u_id'=>$u_id],'visitor_num');

        if( $result ){
            $access = $db->common_select('lawyer_info',['u_id'=>$u_id],'visitor_num');
            $access->avatarurl = $avatarurl;
            jsonout( 200,'success',$access );
        }else{
            jsonout( 500,'失败' );
        }

    }

    /**
     * 获得访问用户头像
     * @param   $u_id   int   [访问用户id]
    */
    private function is_access($db,$u_id){
        $u_img= $db->common_selects('visitor',['u_id'=>$u_id],'visitor_u_id',1,3,'updated_at');
        $img_id = array_column(json_decode($u_img,true),'visitor_u_id');//把对象转化为数组
        if( empty($img_id) ){
            $avatarurl[] = ['avatarurl'=>'https://ybf-dev.oss-cn-hangzhou.aliyuncs.com/lawyer/fanke.png'];
        } else {
            $avatarurl = $db->wherein_selects('user_info',[],'u_id',$img_id,'avatarurl');
        }
        return $avatarurl;
    }

    /**
     * 判断是否有token
    */
    protected function is_token( $request,$inputs ){
        if( !empty($inputs['user_id']) ){
            $u_id = isset($inputs['user_id'])?$inputs['user_id']:'';
        } else {
            $u_id = $request->uid;
        }

        if( empty($u_id) || !is_numeric($u_id) ){
            jsonout( 400,'参数错误' );
        } else {
            return $u_id;
        }

    }

    /**
     * 判断参数是否正确
    */
    private function is_param( $phone,$speciality_ids,$take_office,$law_office_name,$synopsis ){
        if( !is_valid_phone($phone) ){//手机号码是否错误
            jsonout( 400,'手机号码错误' );
        }

//        if( count(explode(',',$speciality_ids))>4 ){ //擅长领域数量判断
//            jsonout( 400,'最多选择4个擅长领域' );
//        }

        $field= explode(',',$take_office);
        if( count($field) >2 ){//专业职称数量判断
            jsonout( 400,'头衔最多2个' );
        }

        for( $i=0;$i<count($field);$i++ ){
            if( mb_strlen($field[$i])>25 ){
                jsonout( 400,'头衔字数最多25个' );
            }
        }

        if( mb_strlen($law_office_name)>15 ){//律所名称数量判断
            jsonout( 400,'律所名称不能超过15个字' );
        }

        if( mb_strlen($synopsis)>800 ){ //简介字数判断
            jsonout( 400,'简介超过800字');
        }
    }




}