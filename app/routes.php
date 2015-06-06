<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


Route::any('/carousel', array('before' => 'guest', 'uses' => 'MainFrameController@carousel'));
Route::any('/cover', array('before' => 'guest', 'uses' => 'MainFrameController@cover'));
Route::any('/testp', array('before' => 'guest', 'uses' => 'MainFrameController@testp'));


Route::get('/get-comments/{ablum_id}', 'MainFrameController@getComments');
Route::post('/add-comments/{ablum_id}', 'MainFrameController@addComments');

Route::post('/switch-like/{ablum_id}/{likeit}', 'MainFrameController@switchLike');

// 后台登录
Route::any('/login', array('before' => 'guest', 'uses' => 'LoginController@login'));

//
// 所有需要账号验证的页面
// 可以理解为，在request进入匿名函数中的每个路由前，都会执行一次名为 'auth' 的代码 (Route::filter('auth', function(){});
//
Route::group(array('before' => 'auth'), function() {
	// 首页入口
	Route::get('/', 'MainFrameController@index');
});
