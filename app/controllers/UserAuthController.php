<?php

class UserAuthController extends Controller {

	static public function guest() {
		return !self::isLogin();
	}
	static public function isLogin() {
		// self::logout();
		if (null == Session::get('user_token.user_id', null)) {
			return false;
		}
		if (null == Session::get('user_token.token', null)) {
			return false;
		}
		return true;
	}
	static public function login($user_id, $token) {
		$user_info = tb_users::where('id', $user_id)->first();
		if (null == $user_info) {
			return '未找到用户！请点击 短信/微信/QQ 中的链接地址访问';
		}

		if ($user_info->licence_count >= 1) {
			// 免登录金牌 登录
			$token = 'licence_count'.$user_info->licence_count;
			tb_users::where('id', $user_id)
				->update(array('licence_count' => $user_info->licence_count-1));
		}
		else {
			// 邀请码 登录
			$user_token_info = tb_user_token::select()
				->where('user_id', $user_id)
				->where('token', $token)
				->first();
			if (null == $user_token_info) {
				return '啊...邀请码不对，请仔细核对一下';
			}

			tb_user_token::where('user_id', $user_id)
				->where('token', $token)
				->update(array('used' => 1));
		}

		Session::put('user_token.user_id', $user_id);
		Session::put('user_token.token', $token);
		return true;
	}
	static public function logout() {
		Session::forget('user_token.user_id');
		Session::forget('user_token.token');
		return true;
	}
	static public function getLoginUserid() {
		$user_id = Session::get('user_token.user_id', null);
		$token = Session::get('user_token.token', null);
		$user_info = tb_users::where('id', $user_id)->first();
		if (null == $user_info) {
			return false;
		}
		return $user_id;
	}
	static public function getLoginInfo() {
		$user_id = Session::get('user_token.user_id', null);
		$token = Session::get('user_token.token', null);
		if (null == $user_id) {
			return false;
		}
		if (null == $token) {
			return false;
		}
		$user_token_info = tb_user_token::select()
			->where('user_id', $user_id)
			->where('token', $token)
			->first()
			->toArray();
		return $user_token_info;
	}
}