<?php
namespace IPCService;

use IPCService\IPCService;
use IPCService\ApplicationStatEntry;


class ApplicationStatArray extends StatArray 
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
			$sortfield = 'l7name';
		}
		if (!$this->ipc->pack_write_u8(IPCService::WTM_IPC_VERSION)) {
			return FALSE;
		}

		if (!$this->ipc->pack_write_u8(IPCService::WTM_COMM_REALTIME_STAT_QUERY)) {
			return FALSE;
		}

		if (!$this->ipc->pack_write_u8(IPCService::WTM_RTS_APPL_OVERVIEW_QUERY)) {
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
		if ($this->ipc->read_unpack_u8() != IPCService::WTM_RTS_APPL_OVERVIEW_REPLY) {
			return FALSE;
		}

		$count = $this->ipc->read_unpack_u32();
		if ($count === FALSE) {
			return FALSE;
		}

		$all_apps = array();
		for ($i = 0; $i < $count; $i++) {
			$s = new ApplicationStatEntry;
			$s->parse($this->ipc);

			if ($filter_l7name) {
				if (strstr($s->l7name, $filter_l7name))
					$all_apps[$s->l7prot] = $s;
			} else {
				$all_apps[$s->l7prot] = $s;
			}
		}

		$this->sort_field = $sortfield;
		$this->sort_order = $sortorder;
		uasort($all_apps, array($this, 'compare'));

		return $all_apps;
	}

	/**
	 * @brief 比较指定字段的值
	 * @param $field 字段名
	 * @param $a ApplicationStatEntry 类型的变量
	 * @param $b ApplicationStatEntry 类型的变量
	 * @return -1:$a<$b  0:$a==$b  1:$a>$b
	 */
	public static function compare_field($field, $a, $b) {
		if ('l7prot' == $field) {
			return strcmp($a->l7prot , $b->l7prot);
		}
		else if ('l7name' == $field) {
			return strcmp($a->l7name , $b->l7name);
		}
		else if ('flow_count' == $field) {
			return $a->flow_count - $b->flow_count;
		}
		else {
			return parent::compare_field($field, $a->stat, $b->stat);
		}
	}
}

