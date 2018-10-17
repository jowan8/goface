<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //jsonout(401,'Unauthorized');
    return view('goface');
});


Route::any('/set_question','TestController@set_question');

/*Route::group(['namespace'=>'Question'],function(){
    Route::any('/question_list','QuestionController@question_list');
});*/