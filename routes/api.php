<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['namespace'=>'Carte','middleware'=>['notCheckToken']],function(){
    Route::any('upload_file',['uses'=>'UploadController@upload_file']); //文件上传
    Route::any('filetest',['uses'=>'UploadController@index']); //文件上传
});



Route::group(['namespace'=>'Carte','middleware'=>['checkToken']],function(){
    Route::any('add_info',['uses'=>'CarteController@add_info']); //添加名片信息
    Route::any('update_info',['uses'=>'CarteController@update_info']); //修改名片信息
    Route::any('is_lawyer',['uses'=>'CarteController@is_lawyer']); //是否是律师
    Route::any('my_lawyer',['uses'=>'CarteController@my_lawyer']); //我的页面
    Route::any('add_praise',['uses'=>'CarteController@add_praise']); //点赞
    Route::any('less_praise',['uses'=>'CarteController@less_praise']); //取消点赞
});

Route::group(['namespace'=>'Cases','middleware'=>['checkToken']],function(){
    Route::any('add_case',['uses'=>'CaseController@add_case']); //添加案例
    Route::any('del_case',['uses'=>'CaseController@del_case']); //删除案例

});


Route::group(['namespace'=>'Payment','middleware'=>['checkToken']],function(){
    Route::any('wechat_pay',['uses'=>'WepayController@wechat_pay']); //微信支付签名
    Route::any('wechat_pay_query',['uses'=>'WepayController@wechat_pay_query']); //微信支付验签
});
Route::any('notify',['uses'=>'Payment\WepayController@notify']); //微信支付异步回调通知

//不验证token   token可有可无
Route::group(['namespace'=>'Carte','middleware'=>['notCheckToken']],function(){

    Route::any('info_show',['uses'=>'CarteController@info_show']); //名片信息展示
    Route::any('field_list',['uses'=>'CarteController@field_list']); //擅长领域
    Route::any('access',['uses'=>'CarteController@access']); //访客数量
    Route::any('is_userid',['uses'=>'CarteController@is_userid']); //默认用户
});

//不验证token   token可有可无
Route::group(['namespace'=>'Cases','middleware'=>['notCheckToken']],function(){
    Route::any('case_list',['uses'=>'CaseController@case_list']); //案例列表
    Route::any('case_details',['uses'=>'CaseController@case_details']); //案例详情
});

//不验证token   token可有可无
Route::group(['namespace'=>'User','middleware'=>['notCheckToken']],function(){
    Route::any('test/curl_test.html','TestController@curlTest');//curl和xss测试
    Route::any('login.html',['uses'=>'LoginController@login']);//登陆
});
//必须验证token
Route::group(['namespace'=>'User','middleware'=>['checkToken']],function(){

});

