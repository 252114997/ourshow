<?php

namespace IPCService;

use IPCService\IPCService;
use IPCService\UserStatEntry;

class UserStatArray extends StatArray 
{
	private $ipc;

	public function __construct($host = null, $port = null) {
		$this->ipc = new IPCService($host, $port);
	}

	public function query($param_array, $sortfield, $sortorder, $filter_user_name) {
		if (null === $param_array) {
			return FALSE;
		}		
		if (null == $sortorder) {
			$sortorder = 'desc';
		}
		if (null == $sortfield) {
			$sortfield = 'user_name';
		}
		if (!$this->ipc->pack_write_u8(IPCService::WTM_IPC_VERSION)) {
			return FALSE;
		}
		if (!$this->ipc->pack_write_u8(IPCService::WTM_COMM_REALTIME_STAT_QUERY)) {
			return FALSE;
		}
		if (!$this->ipc->pack_write_u8(IPCService::WTM_RTS_USER_OVERVIEW_QUERY)) {
			return FALSE;
		}
		
		$num = count($param_array);
		if (!$this->ipc->pack_write_u32($num)){
			return FALSE;
		}
		foreach ($param_array as $param) {
			if (!$this->ipc->pack_write_u32($param['id'])){
				return FALSE;
			}
		}

		if ($this->ipc->read_unpack_u8() != IPCService::WTM_IPC_VERSION) {
			return FALSE;
		}
		if ($this->ipc->read_unpack_u8() != IPCService::WTM_COMM_REALTIME_STAT_REPLY) {
			return FALSE;
		}
		if ($this->ipc->read_unpack_u8() != IPCService::WTM_RTS_USER_OVERVIEW_REPLY) {
			return FALSE;
		}

		$count = $this->ipc->read_unpack_u32();
		if ($count === FALSE) {
			return FALSE;
		}

		$all_users = array();
		for ($i = 0; $i < $count; $i++) {
			$user_entry = new UserStatEntry;
			$user_entry->parse($this->ipc);

			if (  $filter_user_name 
				&& !(strstr($user_entry->user_name, $filter_user_name)) 
				&& !(strstr($user_entry->lan_ip, $filter_user_name)) 
				&& !(strstr($user_entry->lan_mac, $filter_user_name)) 
				&& !(strstr($user_entry->parent_group, $filter_user_name)) 
			) {
				continue;
			} else {
				$all_users[$user_entry->lan_ip] = $user_entry;
			}
		}

		$this->sort_field = $sortfield;
		$this->sort_order = $sortorder;
		uasort($all_users, array($this, 'compare'));

		return $all_users;
	}
	
	/**
	 * @brief 比较指定字段的值
	 * @param $field 字段名
	 * @param $a UserStatEntry 类型的变量
	 * @param $b UserStatEntry 类型的变量
	 * @return -1:$a<$b  0:$a==$b  1:$a>$b
	 */
	public static function compare_field($field, $a, $b) {
		if ('user_name' == $field) {
			return strcmp($a->user_name, $b->user_name);
		}
		else if ('parent_group' == $field) {
			return strcmp($a->parent_group , $b->parent_group);
		}
		else if ('user_desc' == $field) {
			return strcmp($a->user_desc , $b->user_desc);
		}
		else if ('lan_ip' == $field) {
			return ip2long($a->lan_ip) - ip2long($b->lan_ip);
		}
		else if ('lan_mac' == $field) {
			return strcmp($a->lan_mac , $b->lan_mac);
		}
		else if ('flow_count' == $field) {
			return $a->flow_count - $b->flow_count;
		}
		else if ('terminal_type' == $field) {
			return $a->terminal_type - $b->terminal_type;
		}
		else if ('login_time' == $field) {
			return strcmp($a->login_time , $b->login_time);
		}
		else if ('update_time' == $field) {
			return strcmp($a->update_time , $b->update_time);
		}
		else if ('auth_type' == $field) {
			return $a->auth_type - $b->auth_type;
		}
		else if ('login_type' == $field) {
			return $a->login_type - $b->login_type;
		}
		else if ('is_disabled_internet_access' == $field) {
			return $a->is_disabled_internet_access - $b->is_disabled_internet_access;
		}
		else {
			return parent::compare_field($field, $a->stat, $b->stat);
		}
	}
}

