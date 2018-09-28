<?php

namespace App\Http\Controllers\Sign;

use App\Models\Dbcommon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SignController extends Controller
{
    /**
     * 添加招牌信息
     */
    public function sign_add( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $sign_title = isset($inputs['sign_title'])?$inputs['sign_title']:'';
        $address = isset($inputs['address'])?$inputs['address']:'';
        $telephone = isset($inputs['telephone'])?$inputs['telephone']:'';
        $wechat_num = isset($inputs['wechat_num'])?$inputs['wechat_num']:'';
        $wechat_img = isset($inputs['wechat_img'])?$inputs['wechat_img']:'';
        $introduction = isset($inputs['introduction'])?$inputs['introduction']:'';
        $sign_img_arr = isset($inputs['sign_img_json'])?$inputs['sign_img_json']:'';


        if( empty($sign_title)||empty($address)||empty($telephone)||empty($wechat_num)||empty($wechat_img)||empty($sign_img_arr)){
            jsonout( 400,'invalid param' );
        }
        //转存文件
        $wechat_img=storage_path().'/tmp/'.$wechat_img;
        $new_file_path=storage_path().'/upload/'.date('Y-m').'/'.date('d');
        $move_status=move_file($wechat_img,$new_file_path);
        if($move_status==false){
            jsonout( 500,'inner error' );
        }
        $wechat_img='/upload/'.date('Y-m').'/'.date('d').'/'.$wechat_img;


        foreach ($sign_img_arr as $k=>$v){
            $move_status1=move_file(storage_path().$v,$new_file_path);

            if($move_status1==false){
                jsonout( 500,'inner error' );
            }
            $sign_img_arr[$k]='/upload/'.date('Y-m').'/'.date('d').'/'.$v;
        }

        $data = [
            'user_id' => $user_id,
            'is_show' => 1,
            'sign_title' => $sign_title,
            'address' => $address,
            'telephone' => $telephone,
            'wechat_num' => $wechat_num,
            'wechat_img' => $wechat_img,
            'sign_img_json'=>json_encode($sign_img_arr)
        ];

        $db = new Dbcommon();

        $result = $db->common_insert('sign',$data);
        if($result){
            jsonout( 200,'success' );
        }else{
            jsonout( 500,'inner error' );
        }
    }


    /**
     * 更新招牌信息
     */
    public function sign_update( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $id = isset($inputs['id'])?$inputs['id']:0;
        $sign_title = isset($inputs['sign_title'])?$inputs['sign_title']:'';
        $address = isset($inputs['address'])?$inputs['address']:'';
        $telephone = isset($inputs['telephone'])?$inputs['telephone']:'';
        $wechat_num = isset($inputs['wechat_num'])?$inputs['wechat_num']:'';
        $wechat_img = isset($inputs['wechat_img'])?$inputs['wechat_img']:'';
        $introduction = isset($inputs['introduction'])?$inputs['introduction']:'';
        $sign_img_arr = isset($inputs['sign_img_json'])?$inputs['sign_img_json']:'';


        if( empty($sign_title)||empty($address)||empty($telephone)||empty($wechat_num)||empty($wechat_img)||empty($sign_img_arr)||empty($id)){
            jsonout( 400,'invalid param' );
        }


        $new_file_path=storage_path().'/upload/'.date('Y-m').'/'.date('d');

        //查看该文件是不是存在，存在则不更新
        if(!file_exists(storage_path().$wechat_img)){
            //转存文件
            $wechat_img=storage_path().'/tmp/'.$wechat_img;
            $move_status=move_file($wechat_img,$new_file_path);

            if($move_status==false){
                jsonout( 500,'inner error' );
            }

            $wechat_img='/upload/'.date('Y-m').'/'.date('d').'/'.$wechat_img;
        }



        foreach ($sign_img_arr as $k=>$v){

            if(!file_exists(storage_path().$v)){
                $move_status1=move_file(storage_path().$v,$new_file_path);

                if($move_status1==false){
                    jsonout( 500,'inner error' );
                }
                $sign_img_arr[$k]='/upload/'.date('Y-m').'/'.date('d').'/'.$v;
            }

        }

        $data = [
            'user_id' => $user_id,
            'sign_title' => $sign_title,
            'address' => $address,
            'telephone' => $telephone,
            'wechat_num' => $wechat_num,
            'wechat_img' => $wechat_img,
            'sign_img_json'=>json_encode($sign_img_arr)
        ];

        $db = new Dbcommon();

        $result = $db->common_update('sign',['id'=>$id,'user_id'=>$user_id],$data);
        if($result){
            jsonout( 200,'success' );
        }else{
            jsonout( 500,'inner error' );
        }
    }

    /**
     * 招牌信息展示
     */
    public function sign_show( Request $request ){
        $inputs = $request->inputs;
        $page = isset($inputs['page'])?$inputs['page']:1;
        $limit = isset($inputs['limit'])?$inputs['limit']:10;



        $user_id = $request->user_id;
        $db = new Dbcommon();
        $select = '*';
        $sign_show = $db->common_selects('sign',['user_id'=>$user_id,'is_show'=>1],$select,$page,$limit);
        if( $sign_show ){
            jsonout( 200,'success',$sign_show );
        } else {
            jsonout( 500,'请求失败' );
        }
    }


    /**
     * 招牌信息收藏
     */
    public function sign_collect( Request $request ){
        $inputs = $request->inputs;
        $collect_id = isset($inputs['id'])?$inputs['id']:0;
        $collect_to_user_id = isset($inputs['user_id'])?$inputs['user_id']:0;
        $collect_user_id = $inputs->user_id;
        if(empty($collect_id)||empty($collect_to_user_id)){
            jsonout( 400,'invalid param' );
        }

        $data = [
            'collect_id' => $collect_id,
            'collect_user_id' => $collect_user_id,
            //'collect_to_user_id' => $collect_to_user_id,
        ];

        $db = new Dbcommon();
        $collect_status=$db->common_select('sign',$data,'id');
        if($collect_status){
            jsonout( 400,'Repeat request' );
        }

        $data['collect_to_user_id']=$collect_to_user_id;
        $add_status = $db->common_insert('sign',$data);
        if( $add_status ){
            jsonout( 200,'success' );

        } else {
            jsonout( 500,'请求失败' );
        }
    }

    /**
     * 点赞功能
     */
    public function add_praise( Request $request ){
        $inputs = $request->inputs;
        $u_id = isset($inputs['user_id'])?$inputs['user_id']:'';
        $fabulous_u_id = $request->uid;



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
}
