<?php

namespace IPCService;

use IPCService\IPCService;

class SystemOverview extends IPCService 
{
	public $attributes = array();
	public $attributes_length = array();
	public $attributes_name = array();

	public function __construct($host = null, $port = null) {
		parent::__construct($host, $port);
		
		$this->attributes_length[IPCService::WTM_RTS_TRITON_UPTIME] = 4;
		$this->attributes_length[IPCService::WTM_RTS_TCP_FLOW_PROCESSED] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_FLOW_PROCESSED] = 8;
		$this->attributes_length[IPCService::WTM_RTS_USER_ONLINE_CNT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_APPL_ONLINE_CNT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_FLOW_CNT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_FLOW_CNT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_UPSTREAM_BYTE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_DOWNSTREAM_BYTE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_UPSTREAM_PKT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_DOWNSTREAM_PKT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_UPSTREAM_BYTE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_DOWNSTREAM_BYTE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_UPSTREAM_PKT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_DOWNSTREAM_PKT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_UPSTREAM_BYTE_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_DOWNSTREAM_BYTE_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_UPSTREAM_PKT_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_DOWNSTREAM_PKT_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_UPSTREAM_BYTE_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_DOWNSTREAM_BYTE_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_UPSTREAM_PKT_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_DOWNSTREAM_PKT_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_FLOW_CREATE_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_TCP_FLOW_DESTROY_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_FLOW_CREATE_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UDP_FLOW_DESTROY_RATE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UNAUTH_UPSTEAM_DISCARD_BYTE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_BYTE] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UNAUTH_UPSTEAM_DISCARD_PKT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_PKT] = 8;
		$this->attributes_length[IPCService::WTM_RTS_MARKED_FLOW] = 8;
		$this->attributes_length[IPCService::WTM_RTS_UNMARK_FLOW] = 8;

		$this->attributes_name[IPCService::WTM_RTS_TRITON_UPTIME]                 = "WTM_RTS_TRITON_UPTIME";
		$this->attributes_name[IPCService::WTM_RTS_TCP_FLOW_PROCESSED]            = "WTM_RTS_TCP_FLOW_PROCESSED";
		$this->attributes_name[IPCService::WTM_RTS_UDP_FLOW_PROCESSED]            = "WTM_RTS_UDP_FLOW_PROCESSED";
		$this->attributes_name[IPCService::WTM_RTS_USER_ONLINE_CNT]               = "WTM_RTS_USER_ONLINE_CNT";
		$this->attributes_name[IPCService::WTM_RTS_APPL_ONLINE_CNT]               = "WTM_RTS_APPL_ONLINE_CNT";
		$this->attributes_name[IPCService::WTM_RTS_TCP_FLOW_CNT]                  = "WTM_RTS_TCP_FLOW_CNT";
		$this->attributes_name[IPCService::WTM_RTS_UDP_FLOW_CNT]                  = "WTM_RTS_UDP_FLOW_CNT";
		$this->attributes_name[IPCService::WTM_RTS_TCP_UPSTREAM_BYTE]             = "WTM_RTS_TCP_UPSTREAM_BYTE";
		$this->attributes_name[IPCService::WTM_RTS_TCP_DOWNSTREAM_BYTE]           = "WTM_RTS_TCP_DOWNSTREAM_BYTE";
		$this->attributes_name[IPCService::WTM_RTS_TCP_UPSTREAM_PKT]              = "WTM_RTS_TCP_UPSTREAM_PKT";
		$this->attributes_name[IPCService::WTM_RTS_TCP_DOWNSTREAM_PKT]            = "WTM_RTS_TCP_DOWNSTREAM_PKT";
		$this->attributes_name[IPCService::WTM_RTS_UDP_UPSTREAM_BYTE]             = "WTM_RTS_UDP_UPSTREAM_BYTE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_DOWNSTREAM_BYTE]           = "WTM_RTS_UDP_DOWNSTREAM_BYTE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_UPSTREAM_PKT]              = "WTM_RTS_UDP_UPSTREAM_PKT";
		$this->attributes_name[IPCService::WTM_RTS_UDP_DOWNSTREAM_PKT]            = "WTM_RTS_UDP_DOWNSTREAM_PKT";
		$this->attributes_name[IPCService::WTM_RTS_TCP_UPSTREAM_BYTE_RATE]        = "WTM_RTS_TCP_UPSTREAM_BYTE_RATE";
		$this->attributes_name[IPCService::WTM_RTS_TCP_DOWNSTREAM_BYTE_RATE]      = "WTM_RTS_TCP_DOWNSTREAM_BYTE_RATE";
		$this->attributes_name[IPCService::WTM_RTS_TCP_UPSTREAM_PKT_RATE]         = "WTM_RTS_TCP_UPSTREAM_PKT_RATE";
		$this->attributes_name[IPCService::WTM_RTS_TCP_DOWNSTREAM_PKT_RATE]       = "WTM_RTS_TCP_DOWNSTREAM_PKT_RATE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_UPSTREAM_BYTE_RATE]        = "WTM_RTS_UDP_UPSTREAM_BYTE_RATE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_DOWNSTREAM_BYTE_RATE]      = "WTM_RTS_UDP_DOWNSTREAM_BYTE_RATE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_UPSTREAM_PKT_RATE]         = "WTM_RTS_UDP_UPSTREAM_PKT_RATE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_DOWNSTREAM_PKT_RATE]       = "WTM_RTS_UDP_DOWNSTREAM_PKT_RATE";
		$this->attributes_name[IPCService::WTM_RTS_TCP_FLOW_CREATE_RATE]          = "WTM_RTS_TCP_FLOW_CREATE_RATE";
		$this->attributes_name[IPCService::WTM_RTS_TCP_FLOW_DESTROY_RATE]         = "WTM_RTS_TCP_FLOW_DESTROY_RATE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_FLOW_CREATE_RATE]          = "WTM_RTS_UDP_FLOW_CREATE_RATE";
		$this->attributes_name[IPCService::WTM_RTS_UDP_FLOW_DESTROY_RATE]         = "WTM_RTS_UDP_FLOW_DESTROY_RATE";
		$this->attributes_name[IPCService::WTM_RTS_UNAUTH_UPSTEAM_DISCARD_BYTE]   = "WTM_RTS_UNAUTH_UPSTEAM_DISCARD_BYTE";
		$this->attributes_name[IPCService::WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_BYTE] = "WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_BYTE";
		$this->attributes_name[IPCService::WTM_RTS_UNAUTH_UPSTEAM_DISCARD_PKT]    = "WTM_RTS_UNAUTH_UPSTEAM_DISCARD_PKT";
		$this->attributes_name[IPCService::WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_PKT]  = "WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_PKT";
		$this->attributes_name[IPCService::WTM_RTS_MARKED_FLOW]                   = "WTM_RTS_MARKED_FLOW";
		$this->attributes_name[IPCService::WTM_RTS_UNMARK_FLOW]                   = "WTM_RTS_UNMARK_FLOW";
	}
	public function __destruct() {
		parent::__destruct();
	}

	public function query() {
		if (!parent::pack_write_u8(IPCService::WTM_IPC_VERSION)) {
			return FALSE;
		}

		if (!parent::pack_write_u8(IPCService::WTM_COMM_REALTIME_STAT_QUERY))
			return FALSE;

		if (!parent::pack_write_u8(IPCService::WTM_RTS_OVERVIEW_QUERY))
			return FALSE;

		if (parent::read_unpack_u8() != IPCService::WTM_IPC_VERSION)
			return FALSE;

		if (parent::read_unpack_u8() != IPCService::WTM_COMM_REALTIME_STAT_REPLY)
			return FALSE;

		if (parent::read_unpack_u8() != IPCService::WTM_RTS_OVERVIEW_REPLY)
			return FALSE;

		$count = parent::read_unpack_u32();
		if ($count === FALSE)
			return FALSE;

		for ($i = 0; $i < $count; $i++) {

			$attribute_type = parent::read_unpack_u16();
			if ($this->attributes_length[$attribute_type] == 1)
				$this->attributes[$attribute_type] = parent::read_unpack_u8();
			else if ($this->attributes_length[$attribute_type] == 2)
				$this->attributes[$attribute_type] = parent::read_unpack_u16();
			else if ($this->attributes_length[$attribute_type] == 4)
				$this->attributes[$attribute_type] = parent::read_unpack_u32();
			else if ($this->attributes_length[$attribute_type] == 8)
				$this->attributes[$attribute_type] = parent::read_unpack_u64();
		}
		return $this->attributes;
	}

	public function dump() {
		foreach ($this->attributes as $key => $value) {
			echo "\t".$this->attributes_name[$key]. ": ". $value."<br>";
		}
	}
}

////////////////////////////
//$s = new overview_stat(WTM_IPC_SERVER_ADDR, WTM_IPC_PORT);
//$s->query();
//$s->dump();
