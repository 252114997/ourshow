<?php

namespace IPCService;

/**
 * 连接信息
 */
class ConnectionEntry 
{
	public $l4protocol;
	public $l7prot;
	public $lan_ip;
	public $wan_ip;
	public $lan_port;
	public $wan_port;
	
	public $stat;

	public $start_time;

	public function __construct() {
		$this->stat = new StatEntry();
	}

	public function parse(&$ipc) {
		$this->l4protocol = $ipc->read_unpack_u16();
		$this->l7prot = $ipc->read_unpack_u32();
		$this->lan_ip = long2ip($ipc->read_unpack_u32());
		$this->wan_ip = long2ip($ipc->read_unpack_u32());
		$this->lan_port = $ipc->read_unpack_u16();
		$this->wan_port = $ipc->read_unpack_u16();
		$this->stat->parse($ipc);
		$this->start_time = $ipc->read_unpack_u32();
	}

	public function format() {
		$gps_address = \ipLocation::getInstance()->getaddress($this->wan_ip);
		$wan_gps = \Util::gbkToUtf8($gps_address['area1'].' '.$gps_address['area2']);
		return array_merge(
			$this->stat->format(),
			array(
				'l4protocol' => strtoupper(getprotobynumber($this->l4protocol)),
				'l7prot' => $this->l7prot,
				'lan_ip' => $this->lan_ip,
				'wan_ip' => $this->wan_ip,
				'lan_port' => $this->lan_port,
				'wan_port' => $this->wan_port,
				'continue_time' => time() - $this->start_time,
				'start_time' => (float)sprintf('%u', ($this->start_time)),
				'wan_gps' => $wan_gps,
			)
		 );
	}
}