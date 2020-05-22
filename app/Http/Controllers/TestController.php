<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function index(Request $request)
    {
        $work_types = DB::table('work_type')->where('is_show',1)->orderBy('sort','asc')->limit(8)->get();
        $works = DB::table('work')->orderBy('view_times','desc')->limit(10)->get();
        $shows = DB::table('show')->where('is_show',1)->orderBy('sort','asc')->limit(6)->get();
        $banners = DB::table('banner')->where('is_show',1)->orderBy('sort','asc')->limit(6)->get();

        return view('index/index',['title'=>'学习计划','works'=>$works,'work_types'=>$work_types,'shows'=>$shows,'banners'=>$banners]);
    }

    public function jumpTo(Request $request)
    {
        $i = $request->get('style',0);
        if(!$i){
            $i = mt_rand(1,15);
        }
        return view('show/view'.$i,['title'=>'一堆bug网']);
    }

    public function lists(Request $request)
    {

        $type_id = $request->input('type_id',0);
        $sort = $request->input('sort','id');
        $sort_type = $request->input('sort_type','desc');

        $where=[
            'user_id'=>0,
        ];
        if($type_id){
            $where['type_id'] = $type_id;
        }
        $works = DB::table('work as t1')
            ->leftjoin('work_type as t2','t1.type_id','=','t2.id')
            ->where($where)
            ->select('t2.name','t1.created_at','t1.id','t1.work_name','t1.data_url','t1.created_at')
            ->orderBy('t1.'.$sort,$sort_type)
            ->paginate(17);
        return view('work/lists',['title'=>'最新文章','works'=>$works]);
    }

    /*
     * 增加浏览次数
     */
    public function add_views(Request $request)
    {
        $aid = $request->input('aid',0);
        if($aid){
            DB::table('work')->where('id',$aid)->increment('view_times');
        }
        return response()->json(['code'=>200,'data'=>[]],200);
    }

    /*
     * 添加文章
     */
    public function add_work(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->input();
            //validator([$data['work_name'],$data['data_url']],[['string'],['url']]);
            $rs = DB::table('work')->insert($data);
            if($rs){
                return response()->json(['code'=>200,'data'=>[]],200);
            }
            return response()->json(['code'=>500,'data'=>[],'msg'=>'操作失败'],200);
        }
        $work_types =DB::table('work_type')->where('is_show',1)->orderBy('sort','asc')->get();
        return view('work/add_work',['title'=>'添加文章','work_types'=>$work_types]);
    }
}


