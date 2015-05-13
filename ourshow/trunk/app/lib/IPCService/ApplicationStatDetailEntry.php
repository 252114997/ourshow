<?php
namespace IPCService;

class ApplicationUserEntry
{
	public $user_name;
	public $lan_ip;
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
				'user_name' => $this->user_name,
				'lan_ip' => $this->lan_ip,
				'flow_count' => $this->flow_count,
				'connections' => $connection_array,
			)
		);
	}
}

class ApplicationStatDetailEntry
{
	public $app_entry; // ApplicationStatEntry
	public $users = array(); // ApplicationUserEntry

	public function __construct() {
		$this->app_entry = new ApplicationStatEntry();
	}

	public function parse(&$ipc) {
		$this->app_entry->parse($ipc);
		$page_cnt = $ipc->read_unpack_u32();
		for ($j = 0; $j < $page_cnt; $j++) {
			$conn_item = new ConnectionEntry;
			$conn_item->parse($ipc);

			if (array_key_exists($conn_item->lan_ip, $this->users)) {
				$user_item = $this->users[$conn_item->lan_ip];
				$user_item->flow_count += 1;
				$user_item->stat->add($conn_item->stat);
				$user_item->connections[] = $conn_item;
				$this->users[$conn_item->lan_ip] = $user_item;
			}
			else {
				$user_item = new ApplicationUserEntry;
				$user_item->lan_ip = $conn_item->lan_ip;
				$user_item->user_name = \OrgController::findUsernameByIp($conn_item->lan_ip);
				$user_item->flow_count = 1;
				$user_item->stat = $conn_item->stat;
				$user_item->connections[] = $conn_item;
				$this->users[$conn_item->lan_ip] = $user_item;
			}
		}
	}

	public function format() {
		$user_array = array();
		foreach ($this->users as $user_item) {
			$user_array[] = $user_item->format();
		}

		return array_merge(
			$this->app_entry->format(),
			array(
				'users' => $user_array,
			)
		);
	}
}
