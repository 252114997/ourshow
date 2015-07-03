<?php
namespace IPCService;

class UserStatEntry
{
	public $lan_ip;
	public $lan_mac;
	public $stat;
	public $flow_count;
	public $terminal_type;

	public $parent_group;
	public $user_name;
	public $user_desc;
	public $auth_type;
	public $login_type;
	public $login_time;
	public $update_time;

	public function __construct() {
		$this->stat = new StatEntry();
	}

	public function parse(&$ipc, $with_terminal_type = true) {
		$this->lan_mac = sprintf(
			'%02x:%02x:%02x:%02x:%02x:%02x', 
			$ipc->read_unpack_u8(),
			$ipc->read_unpack_u8(),
			$ipc->read_unpack_u8(),
			$ipc->read_unpack_u8(),
			$ipc->read_unpack_u8(),
			$ipc->read_unpack_u8()
		);
		$this->lan_ip = long2ip($ipc->read_unpack_u32());
		$this->stat->parse($ipc);
		$this->flow_count = $ipc->read_unpack_u32();
		if ($with_terminal_type) {
			$this->terminal_type = $ipc->read_unpack_u8();
		}

		// TODO 如果将这些信息改为由triton发送出来，是否效率更高
		$userinfo = \OrgController::findUserinfoByIp($this->lan_ip);
		$this->user_name = $userinfo['user_name'];
		$this->user_desc = $userinfo['user_desc'];
		$this->parent_group = $userinfo['parent_group'];
		$this->auth_type = $userinfo['auth_type'];
		$this->login_type = $userinfo['login_type'];
		$this->login_time = $userinfo['login_time'];
		$this->update_time = $userinfo['update_time'];
		$this->is_disabled_internet_access = $userinfo['is_disabled_internet_access'];
	}

	public function format() {
		return array_merge(
			$this->stat->format(),
			array(
				'lan_ip' => $this->lan_ip,
				'lan_mac' => $this->lan_mac,
				'flow_count' => $this->flow_count,
				'terminal_type' => $this->terminal_type,

				'parent_group' => $this->parent_group,
				'user_name' => $this->user_name,
				'user_desc' => $this->user_desc,

				'auth_type' => $this->auth_type,
				'login_type' => $this->login_type,
				'login_time' => $this->login_time,
				'update_time' => $this->update_time,
				'is_disabled_internet_access' => $this->is_disabled_internet_access,
			)
		 );
	}
}

