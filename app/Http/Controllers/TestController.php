<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController
{
  public function jumpTo(Request $request){
      $i = $request->get('style',0);
      if(!$i){
          $i = 10;
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
}


