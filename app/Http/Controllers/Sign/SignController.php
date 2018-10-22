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
        if( empty($sign_title)||empty($address)||empty($telephone)||empty($wechat_num)||empty($sign_img_arr)){
            jsonout( 400,'invalid param' );
        }
        if($inputs['version'] >= 100) {

            $user_id = $request->user_id;
            $db = new Dbcommon();
            $select = '*';
            $can_add = $db->common_select('sign', ['user_id' => $user_id], $select);
            if ($can_add) {
                jsonout(400, 'only one sign can be added');
            }

            //转存文件
            if ($wechat_img) {
                $wechat_img = public_path() . $wechat_img;
                $new_file_path = public_path() . '/upload/' . date('Y-m') . '/' . date('d');
                $move_status = move_file($wechat_img, $new_file_path);
                if ($move_status == false) {
                    jsonout(500, 'inner error');
                }
                $wechat_img = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $wechat_img;
            }
            if(is_array($sign_img_arr)){
                foreach ($sign_img_arr as $k => $v) {
                    $move_status1 = move_file(public_path() . $v, $new_file_path);

                    if ($move_status1 == false) {
                        jsonout(500, 'inner error');
                    }
                    $sign_img_arr[$k] = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $v;
                }
            }
            $data = [
                'user_id' => $user_id,
                'is_show' => 1,
                'sign_title' => $sign_title,
                'address' => $address,
                'telephone' => $telephone,
                'wechat_num' => $wechat_num,
                'wechat_img' => $wechat_img,
                'introduction' => $introduction,
                'sign_img_json' => json_encode($sign_img_arr)
            ];

            $db = new Dbcommon();

            $result = $db->common_insert('sign', $data);
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

        if($inputs['version'] >= 100) {
            $new_file_path = public_path() . '/upload/' . date('Y-m') . '/' . date('d');

            if($wechat_img) {
                //查看该文件是不是存在，存在则不更新
                if (!file_exists(public_path() . $wechat_img)) {
                    //转存文件
                    $wechat_img = public_path() . '/tmp/' . $wechat_img;
                    $move_status = move_file($wechat_img, $new_file_path);

                    if ($move_status == false) {
                        jsonout(500, 'inner error');
                    }

                    $wechat_img = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $wechat_img;
                }
            }

            foreach ($sign_img_arr as $k => $v) {

                if (!file_exists(public_path() . $v)) {
                    $move_status1 = move_file(public_path() . $v, $new_file_path);

                    if ($move_status1 == false) {
                        jsonout(500, 'inner error');
                    }
                    $sign_img_arr[$k] = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $v;
                }

            }

            $data = [
                'user_id' => $user_id,
                'sign_title' => $sign_title,
                'address' => $address,
                'telephone' => $telephone,
                'wechat_num' => $wechat_num,
                'wechat_img' => $wechat_img,
                'sign_img_json' => json_encode($sign_img_arr)
            ];

            $db = new Dbcommon();

            $result = $db->common_update('sign', ['id' => $id, 'user_id' => $user_id], $data);
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
     * 招牌信息展示
     */
    public function sign_show( Request $request ){
        $inputs = $request->inputs;
        if($inputs['version'] >= 100) {
            $user_id = $request->user_id;
            $db = new Dbcommon();
            $select = '*';
            $sign_show = $db->common_select('sign', ['user_id' => $user_id, 'is_show' => 1], $select);
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
     * 招牌信息收藏
     */
    public function sign_collect( Request $request ){
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
}
