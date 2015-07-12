<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

App::missing(function($exception)
{
	// return Redirect::to('/login');
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (UserAuthController::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::to('/login');
		}
	}
});

// Route::filter('auth.basic', function()
// {
// 	return Auth::basic();
// });

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (UserAuthController::isLogin()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

Route::filter('privilege_edit', function ($route, $request, $menu_id) {
	if (false == MainFrameController::validateMenuRight($menu_id, 'edit')) {
		return Response::json(array('status'=>0, 'msg'=>'无编辑权限'));
	}
});

Route::filter('privilege_view', function ($route, $request, $menu_id) {
	if (false == MainFrameController::validateMenuRight($menu_id, 'view')) {
		return '<h3>无查看权限</h3>';
	}
});