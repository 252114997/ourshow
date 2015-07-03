<?php
namespace IPCService;

class UserAppEntry
{
	public $l7prot;
	public $l7name;
	public $flow_count;
	public $connections = array(); // ConnectionEntry

	public $stat; // StatEntry

	public function __construct() {
		$this->stat = new StatEntry();
	}

	public function format() {
		$connection_array = array();
		foreach ($this->connections as $connection_item) {
			$connection_array[] = $connection_item->format();
		}
		return array_merge(
			$this->stat->format(),
			array(
				'l7prot' => $this->l7prot,
				'l7name' => $this->l7name,
				'flow_count' => $this->flow_count,
				'connections' => $connection_array,
			)
		);
	}
}

class UserStatDetailEntry
{
	public $user_entry; //UserStatEntry
	public $apps = array(); // UserAppEntry

	public function __construct() {
		$this->user_entry = new UserStatEntry();
	}

	public function parse(&$ipc) {
		$this->user_entry->parse($ipc, false);
		$page_cnt = $ipc->read_unpack_u32();
		for ($j = 0; $j < $page_cnt; $j++) {
			$conn_item = new ConnectionEntry;
			$conn_item->parse($ipc);

			if (array_key_exists(intval($conn_item->l7prot), $this->apps)) {
				$app_item = $this->apps[$conn_item->l7prot];
				$app_item->flow_count += 1;
				$app_item->stat->add($conn_item->stat);
				$app_item->connections[] = $conn_item;
				$this->apps[$conn_item->l7prot] = $app_item;
			}
			else {
				$app_item = new UserAppEntry;
				$app_item->l7prot = $conn_item->l7prot;
				$app_item->l7name = ApplicationStatEntry::translate($conn_item->l7prot);
				$app_item->flow_count = 1;
				$app_item->stat = $conn_item->stat;
				$app_item->connections[] = $conn_item;
				$this->apps[$conn_item->l7prot] = $app_item;
			}
		}
	}

	public function format() {
		$app_array = array();
		foreach ($this->apps as $app_item) {
			$app_array[] = $app_item->format();
		}

		return array_merge(
			$this->user_entry->format(),
			array(
				'apps' => $app_array,
			)
		);
	}
}