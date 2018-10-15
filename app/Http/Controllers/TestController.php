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

//招牌
Route::group(['namespace'=>'Sign','middleware'=>['CheckToken']],function(){
    Route::any('sign_add',['uses'=>'SignController@sign_add']); //添加招牌
    Route::any('sign_update',['uses'=>'SignController@sign_update']); //招牌更新
    Route::any('sign_can_add',['uses'=>'SignController@sign_can_add']); //是否可以添加招牌
    Route::any('sign_show',['uses'=>'SignController@sign_show']); //招牌展示
    Route::any('sign_collect',['uses'=>'SignController@sign_collect']); //招牌收藏
});

//展位
Route::group(['namespace'=>'Booth','middleware'=>['CheckToken']],function(){
    Route::any('booth_add',['uses'=>'BoothController@booth_add']); //添加展位
    Route::any('booth_can_add',['uses'=>'BoothController@booth_can_add']); //是否可以添加展位
    Route::any('booth_update',['uses'=>'BoothController@booth_update']); //展位更新
    Route::any('booth_show',['uses'=>'BoothController@booth_show']); //展位展示
    Route::any('booth_collect',['uses'=>'BoothController@booth_collect']); //展位收藏
});

//动态
Route::group(['namespace'=>'Booth','middleware'=>['CheckToken']],function(){
    Route::any('booth_add',['uses'=>'BoothController@booth_add']); //添加展位
    Route::any('booth_can_add',['uses'=>'BoothController@booth_can_add']); //是否可以添加展位
    Route::any('booth_can_add',['uses'=>'BoothController@booth_can_add']); //展位更新
    Route::any('booth_show',['uses'=>'BoothController@booth_show']); //展位更新
    Route::any('booth_collect',['uses'=>'BoothController@booth_collect']); //展位更新
});


