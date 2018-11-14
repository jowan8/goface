<?php

namespace App\Http\Controllers\Booth;

use App\Models\Dbcommon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BoothController extends Controller
{
    /**
     * 添加展位信息
     */
    public function booth_add( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $booth_title = isset($inputs['booth_title'])?$inputs['booth_title']:'';
        $sign_id = isset($inputs['sign_id'])?$inputs['sign_id']:0;
        $booth_img_str = isset($inputs['booth_img_str'])?trim($inputs['booth_img_str'],''):'';
        $little_case_str = isset($inputs['little_case_str'])?$inputs['little_case_str']:'';
        $big_case = isset($inputs['big_case'])?$inputs['big_case']:'';

        if( empty($booth_title)||empty($booth_img_str)||empty($little_case_str)||empty($big_case)||empty($sign_id)){
            jsonout( 400,'invalid param' );
        }
        if($inputs['version']>=100) {
            $new_file_path = public_path() . '/upload/' . date('Y-m') . '/' . date('d');
            $booth_img_arr=explode(',',$booth_img_str);
            foreach ($booth_img_arr as $k => $v) {
                $move_status1 = move_file(public_path() . $v, $new_file_path);

                if ($move_status1 == false) {
                    jsonout(500, 'inner error');
                }
                $booth_img_arr[$k] = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $v;
            }

            $data = [
                'user_id' => $user_id,
                'sign_id' => $sign_id,
                'is_show' => 1,
                'booth_title' => $booth_title,
                'little_case_str' => $little_case_str,
                'big_case' => $big_case,
                'booth_img_str' => implode('',$booth_img_arr)
            ];

            $db = new Dbcommon();

            $result = $db->common_insert('booth', $data);
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
     * 判断是否可添加展位
     */
    public function booth_can_add( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $sign_id = isset($inputs['sign_id'])?$inputs['sign_id']:0;

        if( empty($sign_id)){
            jsonout( 400,'invalid param' );
        }

        if($inputs['version']>=100){
            $db = new Dbcommon();

            $result = $db->common_count('booth', ['user_id' => $user_id, 'sign_id' => $sign_id]);

            if ($result >= 20) {
                $data['can_add'] = 0;
            } else {
                $data['can_add'] = 1;
            }
            jsonout(200, 'success', $data);

        }else{
            jsonout(400, 'wrong version');
        }
    }
    /**
     * 更新展位信息
     */
    public function booth_update( Request $request ){
        $inputs = $request->inputs;
        $user_id = $request->user_id;
        $id = isset($inputs['id'])?$inputs['id']:0;
        $booth_title = isset($inputs['booth_title'])?$inputs['booth_title']:'';
        $booth_img_str = isset($inputs['booth_img_str'])?$inputs['booth_img_str']:'';
        $little_case_str = isset($inputs['little_case_str'])?$inputs['little_case_str']:'';
        $big_case = isset($inputs['big_case'])?$inputs['big_case']:'';


        if( empty($booth_title)||empty($booth_img_str)||empty($little_case_str)||empty($big_case)||empty($id)){
            jsonout( 400,'invalid param' );
        }
        if($inputs['version']>=100) {

            $new_file_path = public_path() . '/upload/' . date('Y-m') . '/' . date('d').'/';
            $booth_img_arr=explode(',',$booth_img_str);
            foreach ($booth_img_arr as $k => $v) {
                if (!file_exists(public_path() . $v)) {

                    $move_status = move_file(public_path() . $v, $new_file_path);

                    if ($move_status == false) {
                        jsonout(500, 'inner error');
                    }
                    $booth_img_arr[$k] = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $v;
                }
            }

            $data = [
                'user_id' => $user_id,
                'booth_title' => $booth_title,
                'little_case_json' => $little_case_str,
                'big_case' => $big_case,
                'booth_img_str' => json_encode($booth_img_arr)
            ];

            $db = new Dbcommon();

            $result = $db->common_update('booth', ['id' => $id, 'user_id' => $user_id], $data);
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
    public function booth_show( Request $request ){
        $inputs = $request->inputs;
        $page = isset($inputs['page'])?$inputs['page']:1;
        $limit = isset($inputs['limit'])?$inputs['limit']:10;
        if($inputs['version']>=100) {

            $user_id = $request->user_id;
            $db = new Dbcommon();
            $select = '*';
            $sign_show = $db->common_selects('booth', ['user_id' => $user_id, 'is_show' => 1], $select, $page, $limit);
            if ($sign_show) {
                jsonout(200, 'success', $sign_show);
            } else {
                jsonout(500, 'inner error');
            }
        }else{
            jsonout(400, 'wrong version');
        }
    }

    /**
     * 展位信息收藏
     */
    public function booth_collect( Request $request ){
        $inputs = $request->inputs;
        $collect_id = isset($inputs['id'])?$inputs['id']:0;
        $collect_type = isset($inputs['collect_type'])?$inputs['collect_type']:0;//0-关注 1-取消关注
        $collect_to_user_id = isset($inputs['user_id'])?$inputs['user_id']:0;//被收藏的用户的ID
        $collect_user_id = $request->user_id;
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

    /**
     * 删除展位信息
     */
    public function booth_del( Request $request ){
        $inputs = $request->inputs;
        $id = isset($inputs['id'])?$inputs['id']:0;
        if(empty($id)){
            json_encode(400,'invalid param');
        }
        $user_id = $request->user_id;
        if($inputs['version']>=100) {
            $db = new Dbcommon();
            $status = $db->common_update('booth', ['user_id' => $user_id, 'id' => $id], ['is_delete'=>1]);
            if ($status) {
                jsonout(200, 'success');
            } else {
                jsonout(500, 'inner error');
            }
        }else{
            jsonout(400, 'wrong version');
        }
    }


}
