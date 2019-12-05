<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController
{
  public function jumpTo(Request $request){
      $i = mt_rand(1,8);
      switch ($i){
          case 1:
              $title = '随鼠标生成多彩粒子,超好看-精美H5动效';
              break;
          case 2:
              $title = '3D立体平面图,超炫酷-精美H5动效';
              break;
          case 3:
              $title = '随鼠快速转动的多彩粒子,超酷-精美H5动效';
              break;
          case 4:
              $title = '随鼠生成的炫酷彩带,超动感-精美H5动效';
              break;
          case 5:
              $title = '鼠标移动产生的灯光效果,朦胧美-精美H5动效';
              break;
          //case 6:
          //    $title = '自动生成多彩水泡,非常漂亮-精美H5动效';
          //    break;
          //case 7:
          //    $title = '动态雪花效果,超美-精美H5动效';
          //    break;
          case 6:
              $title = '炫酷的喷泉灯光效果-精美H5动效';
              break;
          case 7:
              $title = '动态雨滴效果-精美H5动效';
              break;
          case 8:
              $title = '超级好看的烟花效果-精美H5动效';
              break;
          default:
              $title = '啦啦啦';
              break;
      }

      return view('view'.$i,['title'=>$title]);
  }
}


