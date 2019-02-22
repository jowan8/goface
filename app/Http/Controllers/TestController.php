<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController
{
  public function jumpTo(Request $request){
      $i = mt_rand(1,7);
      switch ($i){
          case 1;
              $title = '随鼠标生成多彩粒子,超好看';
              break;
          case 2;
              $title = '3D立体平面图,超炫酷';
              break;
          case 3;
              $title = '随鼠快速转动的多彩粒子,超酷';
              break;
          case 4;
              $title = '随鼠生成的炫酷彩带,超动感';
              break;
          case 5;
              $title = '鼠标移动产生的灯光效果,朦胧美';
              break;
          case 6;
              $title = '自动生成多彩水泡,非常漂亮';
              break;
          case 7;
              $title = '动态雪花效果,超美';
              break;
      }

      return view('view'.$i,['title'=>$title]);
  }
}


