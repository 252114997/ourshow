<?php

namespace IPCService;

use IPCService\IPCService;

class ApplicationStatDetailArray extends StatArray 
{
	private $ipc;

	public function __construct($host = null, $port = null) {
		$this->ipc = new IPCService($host, $port);
	}

	public function query($param_array, $sortfield, $sortorder, $filter_name) {
		if (null === $param_array) {
			return FALSE;
		}
		if (null == $sortorder) {
			$sortorder = 'desc';
		}
		if (null == $sortfield) {
			$sortfield = 'lan_ip';
		}
		if (!$this->ipc->pack_write_u8(IPCService::WTM_IPC_VERSION)) {
			return FALSE;
		}
		if (!$this->ipc->pack_write_u8(IPCService::WTM_COMM_REALTIME_STAT_QUERY)) {
			return FALSE;
		}
		if (!$this->ipc->pack_write_u8(IPCService::WTM_RTS_APPL_DETAIL_QUERY)){
			return FALSE;
		}

		$num = count($param_array);
		if (!$this->ipc->pack_write_u32($num)){
			return FALSE;
		}
		foreach ($param_array as $param) {
			// id 可用于筛选协议 mark 值 
			// offset limit order 这些参数 未在triton中使用
			// ws@2015.03.05
			if (!$this->ipc->pack_write_u32($param['id'])){
				return FALSE;
			}
			if (!$this->ipc->pack_write_u32($param['offset'])){
				return FALSE;
			}
			if (!$this->ipc->pack_write_u32($param['limit'])){
				return FALSE;
			}
			if (!$this->ipc->pack_write_u32($param['order'])){
				return FALSE;
			}
		}

		if ($this->ipc->read_unpack_u8() != IPCService::WTM_IPC_VERSION) {
			return FALSE;
		}
		if ($this->ipc->read_unpack_u8() != IPCService::WTM_COMM_REALTIME_STAT_REPLY) {
			return FALSE;
		}
		if ($this->ipc->read_unpack_u8() != IPCService::WTM_RTS_APPL_DETAIL_REPLY) {
			return FALSE;
		}

		if (FALSE === ($count = $this->ipc->read_unpack_u32())) {
			return FALSE;
		}

		$all_apps = array();
		for ($i = 0; $i < $count; $i++) {
			$s = new ApplicationStatDetailEntry;
			$s->parse($this->ipc);

			$all_apps[$s->app_entry->l7prot] = $s;
		}

		$all_users = array();
		if (null != ($first_item = array_shift($all_apps))) {
			foreach($first_item->users as $key => $user_item) {
				if (  $filter_name 
					&& !(strstr($user_item->user_name, $filter_name))
					&& !(strstr($user_item->lan_ip, $filter_name))
				) {
					unset($first_item->users[$key]);
				}
			}

			$this->sort_field = $sortfield;
			$this->sort_order = $sortorder;
			uasort($first_item->users, array($this, 'compare'));

			// 仅使用应用的 user 信息
			$format_first_item = $first_item->format();
			$all_users = $format_first_item['users'];
		}
		return $all_users;
	}

	/**
	 * @brief 比较指定字段的值
	 * @param $field 字段名
	 * @param $a ApplicationUserEntry 类型的变量
	 * @param $b ApplicationUserEntry 类型的变量
	 * @return -1:$a<$b  0:$a==$b  1:$a>$b
	 */
	public static function compare_field($field, $a, $b) {
		if ('lan_ip' == $field) {
			return ip2long($a->lan_ip) - ip2long($b->lan_ip);
		}
		else if ('user_name' == $field) {
			return strcmp($a->user_name , $b->user_name);
		}
		else if ('flow_count' == $field) {
			return $a->flow_count - $b->flow_count;
		}
		else {
			return parent::compare_field($field, $a->stat, $b->stat);
		}
	}
}
