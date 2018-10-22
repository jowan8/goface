<?php

namespace App\Http\Controllers\Dynamic;

use App\Models\Dbcommon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DynamicController extends Controller
{
    /**
     * 添加动态信息
     */
    public function dynamic_add( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $sign_id = isset($inputs['sign_id'])?$inputs['sign_id']:0;
        $dynamic_title = isset($inputs['dynamic_title'])?$inputs['dynamic_title']:'';
        $dynamic_content = isset($inputs['dynamic_content'])?$inputs['dynamic_content']:'';
        $share_id = isset($inputs['share_id'])?$inputs['share_id']:0;
        $dynamic_img_arr = isset($inputs['dynamic_img_json'])?$inputs['dynamic_img_json']:'';

        if(empty($booth_img_arr)&&empty($dynamic_content)||empty($sign_id)){
            jsonout( 400,'invalid param' );
        }
        if($inputs['version']>=100) {
            $new_file_path = public_path() . '/upload/' . date('Y-m') . '/' . date('d');
            foreach ($dynamic_img_arr as $k => $v) {
                $move_status = move_file(public_path() . $v, $new_file_path);

                if ($move_status == false) {
                    jsonout(500, 'inner error');
                }
                $dynamic_img_arr[$k] = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $v;
            }

            $data = [
                'user_id' => $user_id,
                'sign_id' => $sign_id,
                'is_show' => 1,
                'dynamic_title' => $dynamic_title,
                'share_id' => $share_id,
                'dynamic_content' => $dynamic_content,
                'dynamic_img_json' => json_encode($dynamic_img_arr)
            ];

            $db = new Dbcommon();

            $result = $db->common_insert('dynamic', $data);
            if ($result) {
                jsonout(200, 'success');
            } else {
                jsonout(500, 'inner error');
            }
        }else{
            jsonout(400, 'wrong version');
        }
    }

    /**
     * 判断是否可与你添加动态
     */
    public function dynamic_can_add( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $sign_id = isset($inputs['sign_id'])?$inputs['sign_id']:0;

        if( empty($sign_id)){
            jsonout( 400,'invalid param' );
        }
        if($inputs['version']>=100) {

            $db = new Dbcommon();

            $result = $db->common_count('dynamic', ['user_id' => $user_id, 'sign_id' => $sign_id]);

            if ($result >= 20) {
                $data['can_add'] = 0;
            } else {
                $data['can_add'] = 1;
            }

            if ($result) {
                jsonout(200, 'success', $data);
            } else {
                jsonout(500, 'inner error');
            }
        }else{
            jsonout(400, 'wrong version');
        }
    }

    /**
     * 更新展位信息
     */
    public function dynamic_update( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $id = isset($inputs['id'])?$inputs['id']:0;
        $dynamic_title = isset($inputs['dynamic_title'])?$inputs['dynamic_title']:'';
        $dynamic_content = isset($inputs['dynamic_content'])?$inputs['dynamic_content']:'';
        $share_id = isset($inputs['share_id'])?$inputs['share_id']:0;
        $dynamic_img_arr = isset($inputs['dynamic_img_json'])?$inputs['dynamic_img_json']:'';


        if(empty($booth_img_arr)&&empty($dynamic_content)||empty($id)){
            jsonout( 400,'invalid param' );
        }
        if($inputs['version']>=100) {
            $new_file_path = public_path() . '/upload/' . date('Y-m') . '/' . date('d');
            foreach ($dynamic_img_arr as $k => $v) {
                if (!file_exists(public_path() . $v)) {
                    $move_status = move_file(public_path() . $v, $new_file_path);

                    if ($move_status == false) {
                        jsonout(500, 'inner error');
                    }
                    $dynamic_img_arr[$k] = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $v;
                }
            }

            $data = [
                'user_id' => $user_id,
                'dynamic_title' => $dynamic_title,
                'share_id' => $share_id,
                'dynamic_content' => $dynamic_content,
                'dynamic_img_json' => json_encode($dynamic_img_arr)
            ];

            $db = new Dbcommon();

            $result = $db->common_update('dynamic', ['id' => $id, 'user_id' => $user_id], $data);
            if ($result) {
                jsonout(200, 'success');
            } else {
                jsonout(500, 'inner error');
            }
        }else{
            jsonout(400, 'wrong version');
        }
    }

    /**
     * 展位信息展示
     */
    public function dynamic_show( Request $request ){
        $inputs = $request->inputs;
        $page = isset($inputs['page'])?$inputs['page']:1;
        $limit = isset($inputs['limit'])?$inputs['limit']:10;
        $user_id = $request->user_id;

        if($inputs['version']>=100) {
            $db = new Dbcommon();
            $select = '*';
            $sign_show = $db->common_selects('dynamic', ['user_id' => $user_id, 'is_show' => 1], $select, $page, $limit);
            if ($sign_show) {

                jsonout(200, 'success', $sign_show);
            } else {
                jsonout(500, '请求失败');
            }
        }else{
            jsonout(400, 'wrong version');
        }
    }

    /**
     * 朋友圈信息收藏
     */
    public function sign_collect( Request $request ){
        $inputs = $request->inputs;
        $collect_id = isset($inputs['id'])?$inputs['id']:0;
        $collect_type = isset($inputs['collect_type'])?$inputs['collect_type']:0;//0-关注 1-取消关注
        $collect_to_user_id = isset($inputs['user_id'])?$inputs['user_id']:0;//被收藏的用户的ID
        $collect_user_id = $inputs->user_id;
        if(empty($collect_id)||empty($collect_to_user_id)){
            jsonout( 400,'invalid param' );
        }
        if($inputs['version'] >= 100) {
            $data = [
                'collect_id' => $collect_id,
                'collect_user_id' => $collect_user_id,
                //'collect_to_user_id' => $collect_to_user_id,
            ];

            $db = new Dbcommon();
            $collect_status = $db->common_select('sign', $data, 'id');
            //关注
            if($collect_type==0) {
                if($collect_status){
                    jsonout(400, 'repeat request');
                }
                $data['collect_to_user_id'] = $collect_to_user_id;
                $status = $db->common_insert('sign', $data);
            }elseif($collect_type==1){
                $status = $db->common_delete('sign', $data);
            }else{
                $status=true;
            }

            if($status){
                jsonout(200,'success');
            }else{
                jsonout(500,'failed');
            }

        }else{
            jsonout(400, 'wrong version');
        }
    }

}
