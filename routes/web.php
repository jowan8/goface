<?php
Route::group(['middleware'=>'webRq'],function(){
    Route::get('/','TestController@index');//网页首页
    Route::get('/jumpTo','TestController@jumpTo');//跳转到展示页面
    Route::get('/lists','TestController@lists');//文章列表
    Route::post('/add_views','TestController@add_views');//添加浏览次数
    Route::any('/add_work','TestController@add_work');//添加文章
});
