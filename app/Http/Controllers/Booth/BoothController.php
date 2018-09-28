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
        $booth_img_arr = isset($inputs['booth_img_json'])?$inputs['booth_img_json']:'';
        $little_case_json = isset($inputs['little_case_json'])?$inputs['little_case_json']:'';
        $big_case = isset($inputs['big_case'])?$inputs['big_case']:'';

        if( empty($booth_title)||empty($booth_img_arr)||empty($little_case_json)||empty($big_case)){
            jsonout( 400,'invalid param' );
        }

        $new_file_path=storage_path().'/upload/'.date('Y-m').'/'.date('d');
        foreach ($booth_img_arr as $k=>$v){
            $move_status1=move_file(storage_path().$v,$new_file_path);

            if($move_status1==false){
                jsonout( 500,'inner error' );
            }
            $booth_img_arr[$k]='/upload/'.date('Y-m').'/'.date('d').'/'.$v;
        }

        $data = [
            'user_id' => $user_id,
            'is_show' => 1,
            'booth_title' => $booth_title,
            'little_case_json' => $little_case_json,
            'big_case' => $big_case,
            'booth_img_json'=>json_encode($booth_img_arr)
        ];

        $db = new Dbcommon();

        $result = $db->common_insert('booth',$data);
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
        $booth_title = isset($inputs['booth_title'])?$inputs['booth_title']:'';
        $booth_img_arr = isset($inputs['booth_img_json'])?$inputs['booth_img_json']:'';
        $little_case_json = isset($inputs['little_case_json'])?$inputs['little_case_json']:'';
        $big_case = isset($inputs['big_case'])?$inputs['big_case']:'';


        if( empty($booth_title)||empty($booth_img_arr)||empty($little_case_json)||empty($big_case)||empty($id)){
            jsonout( 400,'invalid param' );
        }

        $new_file_path=storage_path().'/upload/'.date('Y-m').'/'.date('d');
        foreach ($booth_img_arr as $k=>$v){
            if(!file_exists(storage_path().$v)) {

                $move_status = move_file(storage_path() . $v, $new_file_path);

                if ($move_status == false) {
                    jsonout(500, 'inner error');
                }
                $booth_img_arr[$k] = '/upload/' . date('Y-m') . '/' . date('d') . '/' . $v;
            }
        }

        $data = [
            'user_id' => $user_id,
            'booth_title' => $booth_title,
            'little_case_json' => $little_case_json,
            'big_case' => $big_case,
            'booth_img_json'=>json_encode($booth_img_arr)
        ];

        $db = new Dbcommon();

        $result = $db->common_update('booth',['id'=>$id,'user_id'=>$user_id],$data);
        if($result){
            jsonout( 200,'success' );
        }else{
            jsonout( 500,'inner error' );
        }

    }

    /**
     * 展位信息展示
     */
    public function booth_show( Request $request ){
        $inputs = $request->inputs;
        $page = isset($inputs['page'])?$inputs['page']:1;
        $limit = isset($inputs['limit'])?$inputs['limit']:10;



        $user_id = $request->user_id;
        $db = new Dbcommon();
        $select = '*';
        $sign_show = $db->common_selects('booth',['user_id'=>$user_id,'is_show'=>1],$select,$page,$limit);
        if( $sign_show ){
            jsonout( 200,'success',$sign_show );
        } else {
            jsonout( 500,'inner error' );
        }
    }

    /**
     * 展位信息收藏
     */
    public function booth_collect( Request $request )
    {
        $inputs = $request->inputs;
        $collect_id = isset($inputs['id']) ? $inputs['id'] : 0;
        $collect_to_user_id = isset($inputs['user_id']) ? $inputs['user_id'] : 0;
        $collect_user_id = $inputs->user_id;
        if (empty($collect_id) || empty($collect_to_user_id)) {
            jsonout(400, 'invalid param');
        }

        $data = [
            'collect_id' => $collect_id,
            'collect_user_id' => $collect_user_id,
            //'collect_to_user_id' => $collect_to_user_id,
        ];

        $db = new Dbcommon();
        $collect_status = $db->common_select('booth', $data, 'id');
        if ($collect_status) {
            jsonout(400, 'Repeat request');
        }

        $data['collect_to_user_id'] = $collect_to_user_id;
        $add_status = $db->common_insert('booth', $data);
        if ($add_status) {
            jsonout(200, 'success');

        } else {
            jsonout(500, 'inner error');
        }
    }

    /**
     * 展位信息展示
     */
    public function booth_del( Request $request ){
        $inputs = $request->inputs;
        $id = isset($inputs['id'])?$inputs['id']:0;
        if(empty($id)){
            json_encode(400,'invalid param');
        }
        $user_id = $request->user_id;

        $db = new Dbcommon();

        $select = '*';
        $sign_show = $db->common_select('booth',['user_id'=>$user_id,'id'=>$id],$select);
        if( $sign_show ){
            jsonout( 200,'success',$sign_show );
        } else {
            jsonout( 500,'inner error' );
        }
    }


}
