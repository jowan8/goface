<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController
{
  public function jumpTo(Request $request){
      $i=mt_rand(1,5);
      return view('view'.$i);
  }
}


