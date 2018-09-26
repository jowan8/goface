<?php

namespace App\Http\Controllers\Dynamic;

use App\Models\Dbcommon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DynamicController extends Controller
{
    /**
     * 添加展位信息
     */
    public function dynamic_add( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $dynamic_title = isset($inputs['dynamic_title'])?$inputs['dynamic_title']:'';
        $dynamic_content = isset($inputs['dynamic_content'])?$inputs['dynamic_content']:'';
        $share_id = isset($inputs['share_id'])?$inputs['share_id']:0;
        $dynamic_img_arr = isset($inputs['dynamic_img_json'])?$inputs['dynamic_img_json']:'';
        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'wrong version');
        }

        if(empty($booth_img_arr)&&empty($dynamic_content)){
            jsonout( 400,'invalid param' );
        }

        $new_file_path=storage_path().'/upload/'.date('Y-m').'/'.date('d');
        foreach ($dynamic_img_arr as $k=>$v){
            $move_status=move_file(storage_path().$v,$new_file_path);

            if($move_status==false){
                jsonout( 500,'inner error' );
            }
            $dynamic_img_arr[$k]='/upload/'.date('Y-m').'/'.date('d').'/'.$v;
        }

        $data = [
            'user_id' => $user_id,
            'dynamic_title' => $dynamic_title,
            'share_id' => $share_id,
            'dynamic_content' => $dynamic_content,
            'dynamic_img_json'=>json_encode($dynamic_img_arr)
        ];

        $db = new Dbcommon();

        $result = $db->common_insert('dynamic',$data);
        if($result){
            jsonout( 200,'success' );
        }else{
            jsonout( 500,'inner error' );
        }
    }


    /**
     * 更新展位信息
     */
    public function booth_update( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $id = isset($inputs['id'])?$inputs['id']:'';
        $dynamic_title = isset($inputs['dynamic_title'])?$inputs['dynamic_title']:'';
        $dynamic_content = isset($inputs['dynamic_content'])?$inputs['dynamic_content']:'';
        $share_id = isset($inputs['share_id'])?$inputs['share_id']:0;
        $dynamic_img_arr = isset($inputs['dynamic_img_json'])?$inputs['dynamic_img_json']:'';
        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'wrong version');
        }

        if(empty($booth_img_arr)&&empty($dynamic_content)||empty($id)){
            jsonout( 400,'invalid param' );
        }

        $new_file_path=storage_path().'/upload/'.date('Y-m').'/'.date('d');
        foreach ($dynamic_img_arr as $k=>$v){
            $move_status=move_file(storage_path().$v,$new_file_path);

            if($move_status==false){
                jsonout( 500,'inner error' );
            }
            $dynamic_img_arr[$k]='/upload/'.date('Y-m').'/'.date('d').'/'.$v;
        }

        $data = [
            'user_id' => $user_id,
            'dynamic_title' => $dynamic_title,
            'share_id' => $share_id,
            'dynamic_content' => $dynamic_content,
            'dynamic_img_json'=>json_encode($dynamic_img_arr)
        ];

        $db = new Dbcommon();

        $result = $db->common_update('dynamic',['id'=>$id],$data);
        if($result){
            jsonout( 200,'success' );
        }else{
            jsonout( 500,'inner error' );
        }

    }

    /**
     * 展位信息展示
     */
    public function sign_show( Request $request ){
        $inputs = $request->inputs;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        $user_id = $request->user_id;
        $db = new Dbcommon();
        $select = '*';
        $sign_show = $db->common_select('booth',['user_id'=>$user_id],$select);
        if( $sign_show ){

            jsonout( 200,'success',$sign_show );
        } else {
            jsonout( 500,'请求失败' );
        }
    }



}
