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

// Route::any('/carousel', 'MainFrameController@carousel');
// Route::any('/testp', 'MainFrameController@testp');
Route::any('/login', 'MainFrameController@login');
Route::get('/', function() {
	return Redirect::to('/welcome');
});

//
// 所有需要账号验证的页面
// 可以理解为，在request进入匿名函数中的每个路由前，都会执行一次名为 'auth' 的代码 (Route::filter('auth', function(){});
//
Route::group(array('before' => 'auth'), function() {
	Route::any('/welcome', 'MainFrameController@welcome');
	Route::any('/cover', 'MainFrameController@cover');
	Route::any('/timeline', 'MainFrameController@timeline');

	Route::get('/get-comments/{ablum_id}', 'MainFrameController@getComments');
	Route::post('/add-comments/{ablum_id}', 'MainFrameController@addComments');

	Route::post('/switch-like/{ablum_id}/{likeit}', 'MainFrameController@switchLike');

	Route::get('/get-pictures/{ablum_id}', 'MainFrameController@getPictures');
	Route::get('/get-picture/{picture_id}', 'MainFrameController@getPicture');
	Route::get('/get-background/{filename}', 'MainFrameController@getBackground');

});
