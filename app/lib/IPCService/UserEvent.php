<?php

namespace IPCService;

use IPCService\IPCService;

class UserEvent extends IPCService 
{
	public function __construct($host = null, $port = null) {
		parent::__construct($host, $port);
	}

	public function __destruct() {
		parent::__destruct();
	}

	/**
	 * @brief 触发用户事件，用于执行 登录、登出 等操作
	 * @return 0 表示执行成功 false 表示执行失败
	 */
	public function event($event, $ip, $username, $password="") {
		$len = 0;

		if (!parent::pack_write_u8(IPCService::WTM_IPC_VERSION)) {
			return FALSE;
		}

		if (!parent::pack_write_u8(IPCService::WTM_COMM_SYNC_USERINFO))
			return FALSE;

		$len = strlen($username);
		if (!parent::pack_write_u32($len)){
			return FALSE;
		}

		if ($len > 0) {
			if (!parent::pack_write($username, $len)){
				return FALSE;
			}
		}

		$len = strlen($password);
		if (!parent::pack_write_u32($len)){
			return FALSE;
		}

		if ($len > 0) {
			if (!parent::pack_write($password, $len)){
				return FALSE;
			}
		}
	
		if (!parent::pack_write_u32(ip2long($ip))){
			return FALSE;
		}

		if ($event == "login") {
			$e = 0;
		} else if ($event == "logout") {
			$e = 1;
		} else if ($event == "update") {
			$e = 2;
		} else {
			return FALSE;
		}

		if (!parent::pack_write_u8($e))
			return FALSE;


		if (parent::read_unpack_u8() != IPCService::WTM_IPC_VERSION)
			return FALSE;

		if (parent::read_unpack_u8() != IPCService::WTM_COMM_SYNC_USERINFO)
			return FALSE;

		return parent::read_unpack_u8();
	}

	/**
	 * @brief 触发用户事件，用于执行 截屏 
	 * @return true 表示执行成功 false 表示执行失败
	 */
	public function screen($ip, $username) {
		$len = 0;

		if (!parent::pack_write_u8(IPCService::WTM_IPC_VERSION)) {
			return FALSE;
		}

		if (!parent::pack_write_u8(IPCService::WTM_COMM_SCREEN_SHOT_CTRL))
			return FALSE;

		$len = strlen($username);
		if (!parent::pack_write_u32($len)){
			return FALSE;
		}

		if ($len > 0) {
			if (!parent::pack_write($username, $len)){
				return FALSE;
			}
		}

		if (!parent::pack_write_u32(ip2long($ip))){
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @brief 触发用户事件，用于执行 启用/禁用上网
	 * @return true 表示执行成功 false 表示执行失败
	 */
	public function allow_internet_access($ip) {
		$len = 0;

		if (!parent::pack_write_u8(IPCService::WTM_IPC_VERSION)) {
			return FALSE;
		}

		if (!parent::pack_write_u8(IPCService::WTM_COMM_SWITCH_INTERNET_ACCESS))
			return FALSE;

		if (!parent::pack_write_u32(ip2long($ip))){
			return FALSE;
		}
		
		\Util::log_warn(__METHOD__.' '."allow_internet_access success()!");

		return TRUE;
	}
}

//$e = new user_event(WTM_IPC_SERVER_ADDR, WTM_IPC_PORT);
//$r=$e->event("login", "test_lyx", "123456", "192.168.100.24");
//echo "login return: ".$r."\n";
//$r=$e->event("update", "tyc", "", "192.168.1.11");
//echo "update return: ".$r."<br>";
//$r=$e->event("logout", "tyc", "", "192.168.1.11");
//echo "logout return: ".$r."<br>";
//$e = new user_event(WTM_IPC_SERVER_ADDR, WTM_IPC_PORT);
//$e->screen("tyc", "192.168.1.121");
//$e->event("logout", "tyctest", "", "192.168.1.121");
