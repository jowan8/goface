<?php

namespace App\Http\Middleware;

use App\Models\Dbcommon;
use Closure;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input=file_get_contents('php://input');//接受数据流 不能接收form-data
        if(empty($input)){
            //处理form-data
            $inputs=$_POST;
        }else{
            $inputs=json_decode($input,true);//处理raw-json
            if(is_null($inputs)){
                parse_str($input,$inputs);//处理x-www.form-urlencoded
            }
        }
        //判断版本
        if( (!isset($inputs['version']))||empty(trim($inputs['version'])) ){
            jsonout(400,'wrong version code');
        }
        //判断token
        if(isset($inputs['token'])&&(!empty(trim($inputs['token'])))){
            $db=new Dbcommon();
            $rs=$db->common_select('user_info',['token'=>$inputs['token']],['user_id']);
            if(empty($rs)){
                jsonout(401,'forbidden');
            }

            $request->uid=$rs->user_id;
            $request->inputs=$inputs;

        }else{
            jsonout(401,'forbidden');
        }
        return $next($request);
    }
}
