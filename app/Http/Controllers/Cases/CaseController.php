<?php
namespace  App\Http\Controllers\Cases;

use App\Http\Controllers\Controller;
use App\Models\Dbcommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CaseController extends Controller{

    /**
     * 添加案例
     */
    public function add_case( Request $request ){
        $inputs = $request->inputs;
        $u_id = $request->uid;
        $case_title = isset($inputs['case_title'])?$inputs['case_title']:'';
        $case_content = isset($inputs['case_content'])?$inputs['case_content']:'';

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        if( empty($case_title) ||empty($case_content) ){
            jsonout( 400,'参数不能为空' );
        }

        if( mb_strlen($case_title)>35 ){
            jsonout( 400,'标题不能超过35个字' );
        }

        if( mb_strlen($case_content)>3000 ){
            jsonout( 400,'内容不能超过3000个字' );
        }

        $db = new Dbcommon();
        $law_id = $db->common_select('lawyer_info',['u_id'=>$u_id],'id');

        $data = [
            'law_id' => $law_id->id,
            'case_title' => $case_title,
            'case_content' => $case_content
        ];

        $rusult = $db->common_insert('case',$data);
        if( $rusult ){
            jsonout( 200,'success' );
        }else{
            jsonout( 500,'失败' );
        }
    }

    /**
     * 案例列表
     */
    public function case_list( Request $request ){
        $inputs = $request->inputs;
        $u_id = $this->is_token( $request,$inputs );
        $page = isset($inputs['page'])?$inputs['page']:1;
        $limit = isset($inputs['limit'])?$inputs['limit']:20;

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        $db = new Dbcommon();
        $cases = $db->common_join_selects('case','lawyer_info','law_id','id',['u_id'=>$u_id],['t1.id as case_id','case_title','case_content','t1.updated_at'],$page,$limit,'t1.updated_at');

        if( $cases ){
            foreach( $cases as $val ){
                $val->updated_at = date('Y年m月d日',strtotime($val->updated_at));
            }
            jsonout( 200,'success',$cases );
        }else{
            jsonout( 500,'失败' );
        }
    }

    /**
     * 案例详情
     */
    public function case_details( Request $request ){
        $inputs = $request->inputs;
        $case_id = isset($inputs['case_id'])?$inputs['case_id']:'';

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        if( empty($case_id)||!is_numeric($case_id) ){
            jsonout( 400,'参数错误');
        }

        $db = new Dbcommon();
        $cases = $db->common_select('case',['id'=>$case_id],['case_title','case_content','created_at']);

        if( $cases ){
            $cases->created_at = date('Y年m月d日',strtotime($cases->created_at));
            jsonout( 200,'success',$cases );
        }else{
            jsonout( 500,'失败' );
        }
    }

    /**
     * 删除案例
     */
    public function del_case( Request $request ){
        $inputs = $request->inputs;
        $case_id = isset($inputs['case_id'])?$inputs['case_id']:'';

        if( !$inputs['version']>='1.0.0' ){
            jsonout( 400,'版本错误');
        }

        if( empty($case_id)||!is_numeric($case_id) ){
            jsonout( 400,'参数错误');
        }

        $db = new Dbcommon();
        $select = ['id','law_id','case_title','case_content','created_at','updated_at'];
        $case = $db->common_select('case',['id'=>$case_id],$select);
        if( !$case ){
            jsonout( 400,'数据有误');
        }
        $data = [
            'table_name'=> 'case',
            'table_id' => $case_id,
            'backup_data' => json_encode($case)
        ];
        DB::beginTransaction();
        $backup = $db->common_insert('backups',$data);
        $del_case = $db->common_delete('case',['id'=>$case_id]);
        if( $backup&&$del_case ){
            DB::commit();
            jsonout( 200,'success' );
        }else{
            DB::rollback();
            jsonout( 500,'失败' );
        }
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



}


