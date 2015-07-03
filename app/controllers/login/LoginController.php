<?php

class LoginController extends BaseController {

	public function login()
	{
		if (Request::isMethod('get')) {
			$company = DB::table('tb_global_settings')
							->where('name', 'company')
							->first();

			$product = DB::table('tb_product')
							->join('tb_global_settings', function($join) {
								$join->on('tb_product.name', '=', 'tb_global_settings.value')
									 ->where('tb_global_settings.name', '=', 'product');
							})
							->select('tb_product.description')
							->first();

			return View::make('login.login')
					->with('product', ($company ? $company->value : '').($product ? $product->description : ''));
		}
		else if (Request::isMethod('post')) {
			$ip_string = Request::getClientIp(true);
			$user = array (
				'username' => Input::get('username'),
				'password' => Input::get('password'),
			);
			$remember = (Input::has('remember_me')) ? true : false;

			if (false !== ($msg = SysAccountFrozenController::isFrozenIp($ip_string))) {
				return Redirect::to('/login')
					->with('login_error_info', '禁止登录！此IP（'.$ip_string.'）已被冻结！<br/>'.$msg);
			}

			if (false === SysAccountController::isInBindip($user['username'], $ip_string)) {
				return Redirect::to('/login')
					->with('login_error_info', '禁止登录！此账户已绑定IP！');
			}

			if (Auth::attempt($user, $remember)) {
				SysAccountFrozenController::delRecord($ip_string);
				return Redirect::to('/')
					->with('login_ok_info', $user['username'].'登录成功');
			}
			else {
				SysAccountFrozenController::appendRecord($ip_string);
				return Redirect::to('/login')
					->with('login_error_info', '用户名或者密码错误')
					->withInput();
			}
		}
		else {
			return Response::make('访问login页面的方法只能是GET/POST方法!', 404);
		}
	}

	public function logout() 
	{
		Auth::logout();

		return Redirect::to('/login');
	}	
}