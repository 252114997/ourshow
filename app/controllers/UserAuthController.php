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
	static public function logout() {
		Session::forget('user_token.user_id');
		Session::forget('user_token.token');
		return true;
	}
	static public function getLoginUserid() {
		$user_info = self::getLoginInfo();
		return $user_info['user_id'];
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