<?php

namespace IPCService;

use IPCService\IPCService;
use IPCService\UserStatEntry;

class UserStatDetailArray extends StatArray 
{
	private $ipc;

	public function __construct($host = null, $port = null) {
		$this->ipc = new IPCService($host, $port);
	}

	public function query($param_array, $sortfield, $sortorder, $filter_l7name) {
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
		if (!$this->ipc->pack_write_u8(IPCService::WTM_RTS_USER_DETAIL_QUERY)){
			return FALSE;
		}

		$num = count($param_array);
		if (!$this->ipc->pack_write_u32($num)){
			return FALSE;
		}
		foreach ($param_array as $param) {
			// id 可用于筛选用户 lan_ip 值 
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
		if ($this->ipc->read_unpack_u8() != IPCService::WTM_RTS_USER_DETAIL_REPLY) {
			return FALSE;
		}

		if (FALSE === ($count = $this->ipc->read_unpack_u32())) {
			return FALSE;
		}
		$all_users = array();
		for ($i = 0; $i < $count; $i++) {
			$s = new UserStatDetailEntry;
			$s->parse($this->ipc);

			$all_users[$s->user_entry->lan_ip] = $s;
		}

		$all_apps = array();
		if (null != ($first_item = array_shift($all_users))) {
			foreach($first_item->apps as $key => $app_item) {
				if ($filter_l7name && !(strstr($app_item->l7name, $filter_l7name))) {
					unset($first_item->apps[$key]);
				}
			}

			$this->sort_field = $sortfield;
			$this->sort_order = $sortorder;
			uasort($first_item->apps, array($this, 'compare'));

			// 仅返回 apps 信息
			$format_first_item = $first_item->format();
			$all_apps = $format_first_item['apps'];
		}

		return $all_apps;
	}

	/**
	 * @brief 比较指定字段的值
	 * @param $field 字段名
	 * @param $a UserAppEntry 类型的变量
	 * @param $b UserAppEntry 类型的变量
	 * @return -1:$a<$b  0:$a==$b  1:$a>$b
	 */
	public static function compare_field($field, $a, $b) {
		if ('l7name' == $field) {
			return strcmp($a->l7name , $b->l7name);
		}
		else if ('flow_count' == $field) {
			return $a->flow_count - $b->flow_count;
		}
		else if ('l7prot' == $field) {
			return $a->l7prot - $b->l7prot;
		}
		else {
			return parent::compare_field($field, $a->stat, $b->stat);
		}
	}
}
