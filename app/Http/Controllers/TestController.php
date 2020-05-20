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

        return view('index',['title'=>'学习计划','works'=>$works,'work_types'=>$work_types,'shows'=>$shows]);
    }

    public function jumpTo(Request $request)
    {
      //$i = $request->get('style',0);
      //if(!$i){
      //    $i = 10;
      //}
            $i = $request->get('style',0);
            if(!$i){
                $i = mt_rand(1,15);
            }

 /*     switch ($i){
          case 1:
              $title = '随鼠标生成多彩粒子,超好看-精美H5动效';//1
              break;
          case 2:
              $title = '3D立体平面图,超炫酷-精美H5动效';//1
              break;
          case 3:
              $title = '随鼠快速转动的多彩粒子,超酷-精美H5动效';//1
              break;
          case 4:
              $title = '随鼠生成的炫酷彩带,超动感-精美H5动效';//1
              break;
          case 5:
              $title = '鼠标移动产生的灯光效果,朦胧美-精美H5动效';//1
              break;
          case 6:
              $title = '炫酷的喷泉灯光效果-精美H5动效';
              break;
          case 7:
              $title = '动态雨滴效果-精美H5动效';
              break;
          case 8:
              $title = '超级好看的烟花效果-精美H5动效';//1
              break;
          case 9:
              $title = '404';//1
              break;
          case 10:
              $title = '10';
              break;
          case 11:
              $title = '11';//1
              break;
          case 12:
              $title = '12';//1
              break;
          case 13:
              $title = '13';//1
              break;
          case 14:
              $title = '14';//1
              break;
          default:
              $title = '啦啦啦';
              break;
      }
*/
      return view('view'.$i,['title'=>'一堆bug网']);
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
            ->paginate(1);
        return view('lists',['title'=>'最新文章','works'=>$works]);
    }

    public function add_views(Request $request)
    {
        $aid = $request->input('aid',0);
        if($aid){
            DB::table('work')->where('id',$aid)->increment('view_times');
        }
        return response()->json(['code'=>200,'data'=>[]],200);
    }
}


