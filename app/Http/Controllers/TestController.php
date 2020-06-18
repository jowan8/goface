<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * 网站首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $work_types = DB::table('work_type')->where('is_show',1)->orderBy('sort','asc')->limit(8)->get();
        $works = DB::table('work')->orderBy('view_times','desc')->limit(10)->get();
        $shows = DB::table('show')->where('is_show',1)->orderBy('sort','asc')->limit(6)->get();
        $banners = DB::table('banner')->where('is_show',1)->orderBy('sort','asc')->limit(6)->get();

        return view('index/index',['title'=>'学习计划','works'=>$works,'work_types'=>$work_types,'shows'=>$shows,'banners'=>$banners]);
    }

    /**
     * 动效
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function jumpTo(Request $request)
    {
        $i = $request->get('style',0);
        if(!$i){
            $i = mt_rand(1,15);
        }
        return view('show/view'.$i,['title'=>'一堆bug网']);
    }

    /**
     * 学习文章列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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


    public function courses(Request $request)
    {
        $type_id = $request->input('type_id',0);
        $sort = $request->input('sort','id');
        $sort_type = $request->input('sort_type','desc');

        $where=[
            'is_show'=>0,
        ];
        /*
        if($type_id){
            $where['type_id'] = $type_id;
        }
        */
        $works = DB::table('course')
            ->where($where)
            ->orderBy($sort,$sort_type)
            ->paginate(10);
        return view('course/courses',['title'=>'课程列表','works'=>$works]);
    }

    /**
     * 课程详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function course_detail(Request $request){
        $course_id = $request->input('course_id',1);
        $chapter = DB::table('chapter')
            ->where(['course_id'=>$course_id,'main_id'=>0])
            ->select('id','video_time','course_id','main_id','chapter_title','chapter_desc','course_url','course_video_url')
            ->orderBy('id','asc')
            ->get();
        foreach ($chapter as $k=>$v){
            $chapter[$k]->sub_chapter = DB::table('chapter')
                ->where(['course_id'=>$course_id,'main_id'=>$v->id])
                ->select('video_time','course_id','main_id','chapter_title','chapter_desc','course_url','course_video_url')
                ->orderBy('id','asc')
                ->get();
        }
//dd($chapter);
        return view('course/detail',['title'=>'课程详情','chapter'=>$chapter]);

    }


    /**
     * 首页数据分析
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function analyze(Request $request)
    {
        $type = $request->input('type',1);
        switch ($type){
            case 1:
                $data['time'][0] = '4时';
                $data['data'][0] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 03:59:59')])
                    ->count();
                $data['time'][1] = '8时';
                $data['data'][1] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 04:00:00'),date('Y-m-d 07:59:59')])
                    ->count();
                $data['time'][2] = "12时";
                $data['data'][2] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 08:00:00'),date('Y-m-d 13:59:59')])
                    ->count();
                $data['time'][3] = "16时";
                $data['data'][3] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 12:00:00'),date('Y-m-d 15:59:59')])
                    ->count();
                $data['time'][4] = "20时";
                $data['data'][4] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 16:00:00'),date('Y-m-d 19:59:59')])
                    ->count();
                $data['time'][5] = "24时";
                $data['data'][5] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 20:00:00'),date('Y-m-d 23:59:59')])
                    ->count();

                $data['type'] = 'bar';
                $data['color'] = '#87cefa';


                break;
            case 2:
                $data['time'][0] = date('m/d',strtotime ( '-6 days' ));
                $data['data'][0] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-6 days' )),date('Y-m-d 23:59:59',strtotime ( '-6 days' ))])
                    ->count();
                $data['time'][1] = date('m/d',strtotime ( '-5 days' ));
                $data['data'][1] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-5 days' )),date('Y-m-d 23:59:59',strtotime ( '-5 days' ))])
                    ->count();
                $data['time'][2] = date('m/d',strtotime ( '-4 days' ));
                $data['data'][2] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-4 days' )),date('Y-m-d 23:59:59',strtotime ( '-4 days' ))])
                    ->count();
                $data['time'][3] = date('m/d',strtotime ( '-3 days' ));
                $data['data'][3] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-3 days' )),date('Y-m-d 23:59:59',strtotime ( '-3 days' ))])
                    ->count();
                $data['time'][4] = date('m/d',strtotime ( '-2 days' ));
                $data['data'][4] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-2 days' )),date('Y-m-d 23:59:59',strtotime ( '-2 days' ))])
                    ->count();
                $data['time'][5] = date('m/d',strtotime ( '-1 days' ));
                $data['data'][5] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-1 days' )),date('Y-m-d 23:59:59',strtotime ( '-1 days' ))])
                    ->count();
                $data['time'][6] = date('m/d');
                $data['data'][6] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')])
                    ->count();

                $data['type'] = 'bar';
                $data['color'] = '#da70d6';

                break;
            case 3:
                $data['time'][0] = date('m/d',strtotime ( '-34 days' ));
                $data['data'][0] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-34 days' )),date('Y-m-d 23:59:59',strtotime ( '-27 days' ))])
                    ->count();
                $data['time'][1] = date('m/d',strtotime ( '-27 days' ));
                $data['data'][1] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-27 days' )),date('Y-m-d 23:59:59',strtotime ( '-20 days' ))])
                    ->count();
                $data['time'][2] = date('m/d',strtotime ( '-20 days' ));
                $data['data'][2] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-20 days' )),date('Y-m-d 23:59:59',strtotime ( '-13 days' ))])
                    ->count();
                $data['time'][3] = date('m/d',strtotime ( '-13 days' ));
                $data['data'][3] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-13 days' )),date('Y-m-d 23:59:59',strtotime ( '-6 days' ))])
                    ->count();
                $data['time'][4] = date('m/d',strtotime ( '-6 days' ));
                $data['data'][4] = DB::table('request')
                    ->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime ( '-6 days' )),date('Y-m-d 23:59:59')])
                    ->count();

                $data['type'] = 'line';
                $data['color'] = '#ff7f50';

                break;
        }



        return response()->json(['code'=>200,'data'=>['analyze'=>$data]],200);
    }
}


