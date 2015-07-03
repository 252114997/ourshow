<?php

namespace IPCService;

/**
 * 跟triton通信的基础类
 */
class IPCService implements IPC
{
	const WTM_RTS_TRITON_UPTIME = 0;
	const WTM_RTS_TCP_FLOW_PROCESSED = 1;
	const WTM_RTS_UDP_FLOW_PROCESSED = 2;
	const WTM_RTS_USER_ONLINE_CNT = 3;
	const WTM_RTS_APPL_ONLINE_CNT = 4;
	const WTM_RTS_TCP_FLOW_CNT = 5;
	const WTM_RTS_UDP_FLOW_CNT = 6;
	const WTM_RTS_TCP_UPSTREAM_BYTE = 7;
	const WTM_RTS_TCP_DOWNSTREAM_BYTE = 8;
	const WTM_RTS_TCP_UPSTREAM_PKT = 9;
	const WTM_RTS_TCP_DOWNSTREAM_PKT = 10;
	const WTM_RTS_UDP_UPSTREAM_BYTE = 11;
	const WTM_RTS_UDP_DOWNSTREAM_BYTE = 12;
	const WTM_RTS_UDP_UPSTREAM_PKT = 13;
	const WTM_RTS_UDP_DOWNSTREAM_PKT = 14;
	const WTM_RTS_TCP_UPSTREAM_BYTE_RATE = 15;
	const WTM_RTS_TCP_DOWNSTREAM_BYTE_RATE = 16;
	const WTM_RTS_TCP_UPSTREAM_PKT_RATE = 17;
	const WTM_RTS_TCP_DOWNSTREAM_PKT_RATE = 18;
	const WTM_RTS_UDP_UPSTREAM_BYTE_RATE = 19;
	const WTM_RTS_UDP_DOWNSTREAM_BYTE_RATE = 20;
	const WTM_RTS_UDP_UPSTREAM_PKT_RATE = 21;
	const WTM_RTS_UDP_DOWNSTREAM_PKT_RATE = 22;
	const WTM_RTS_TCP_FLOW_CREATE_RATE = 23;
	const WTM_RTS_TCP_FLOW_DESTROY_RATE = 24;
	const WTM_RTS_UDP_FLOW_CREATE_RATE = 25;
	const WTM_RTS_UDP_FLOW_DESTROY_RATE = 26;
	const WTM_RTS_UNAUTH_UPSTEAM_DISCARD_BYTE = 27;
	const WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_BYTE = 28;
	const WTM_RTS_UNAUTH_UPSTEAM_DISCARD_PKT = 29;
	const WTM_RTS_UNAUTH_DOWNSTEAM_DISCARD_PKT = 30;
	const WTM_RTS_MARKED_FLOW = 31;
	const WTM_RTS_UNMARK_FLOW = 32;
	const WTM_RTS_MAX = 33;

	const WTM_RTS_OVERVIEW_QUERY = 0;
	const WTM_RTS_OVERVIEW_REPLY = 1;
	const WTM_RTS_APPL_OVERVIEW_QUERY = 2;
	const WTM_RTS_APPL_OVERVIEW_REPLY = 3;
	const WTM_RTS_USER_OVERVIEW_QUERY = 4;
	const WTM_RTS_USER_OVERVIEW_REPLY = 5;
	const WTM_RTS_APPL_DETAIL_QUERY = 6;
	const WTM_RTS_APPL_DETAIL_REPLY = 7;
	const WTM_RTS_USER_DETAIL_QUERY = 8;
	const WTM_RTS_USER_DETAIL_REPLY = 9;

	const WTM_COMM_UNKNOWN = 0;
	const WTM_COMM_LOGIN = 1;
	const WTM_COMM_LOGOUT = 2;
	const WTM_COMM_KEEP_ALIVE = 3;
	const WTM_COMM_RELOAD_IP_n_MAC = 4;
	const WTM_COMM_SYNC_USERINFO = 5;
	const WTM_COMM_SYNC_USER_LOGIN = 6;
	const WTM_COMM_AUTH_SWITCH = 7;
	const WTM_COMM_ICMP_SWITCH = 8;
	const WTM_COMM_DNS_SWITCH = 9;
	const WTM_COMM_RELOAD_PATTERN = 10;
	const WTM_COMM_RELOAD_ACL = 11;
	const WTM_COMM_UPLOAD_QQCHAT = 12;
	const WTM_COMM_LOG_LEVEL = 13;
	const WTM_COMM_REALTIME_STAT_QUERY = 14;
	const WTM_COMM_REALTIME_STAT_REPLY = 15;
	const WTM_COMM_PROC_SYNC = 16;
	const WTM_COMM_PROC_ACL = 17;
	const WTM_COMM_SECURITY = 18;
	const WTM_COMM_DEVICE = 19;
	const WTM_COMM_DUMP_AUTH_INFO = 20;
	const WTM_COMM_UPLOAD_QQCHAT_V2 = 21;
	const WTM_COMM_PSWD_MODIFY = 22;
	const WTM_COMM_LOGIN_V2 = 23;
	const WTM_COMM_SCREEN_SHOT_CTRL = 24;
	const WTM_COMM_SCREEN_SHOT_DATA = 25;
	const WTM_COMM_SWITCH_INTERNET_ACCESS = 39;

	const RES_OK =  0;
	const RES_USER_ERROR =  1;
	const RES_PASSWORD_ERROR =  2;
	const RES_BIND_IP_ERROR =  3;
	const RES_BIND_MAC_ERROR =  4;
	const RES_MULTIPLE_LOGIN_UNALLOW_ERROR =  5;
	const RES_LOGIN_COUNT_EXCEED_ERROR =  6;
	const RES_MAC_CHANGED_ERROR =  7;
	const RES_LOGIN_KICK_OFF_ERROR =  8;
	const RES_SAME_ACCOUNT_ON_SAME_IP_ERROR =  9;
	const RES_BIND_COMPUTER_ERROR =  10;
	const RES_LOGIN_TERM_ERROR =  11;
	const RES_UNKNOWN_ERROR =  12;

	const WTM_IPC_VERSION = 1;
	const WTM_IPC_RECV_TIMEOUT =  2;

	protected $socket = null;
	protected $connected = FAlSE;

	public function __construct($host, $port) {
		if (null === $host) {
			$host = \Config::get('triton.host');
		}
		if (null === $port) {
			$port = \Config::get('triton.port');
		}

		$triton_cnt = 1;
		if (strstr($host, '127.0.0.1') || strstr($host, 'localhost')) {
			$triton_cnt = exec("ps -C triton --no-header | wc -l");
		}
		if ($triton_cnt > 0) {
			$this->socket = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP );
			$this->connected = socket_connect ( $this->socket, $host, $port );
			if (self::WTM_IPC_RECV_TIMEOUT) {
				socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => self::WTM_IPC_RECV_TIMEOUT, "usec" => 0));
			}
		}
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' '."connected $host:$port error!",2);
		}
		else {
			\Util::log_debug(__METHOD__.' '."connected $host:$port success!",2);
		}
	}

	public function __destruct() {
		$this->socket && socket_close($this->socket);
		$this->socket=NULL;
	}

	public function pack_write_u8($num) {
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		return socket_write($this->socket, pack("C", $num));
	}

	public function pack_write_u16($num) {
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		return socket_write($this->socket, pack("n", $num));
	}

	public function pack_write_u32($num) {
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		return socket_write($this->socket, pack("N", $num));
	}

	public function pack_write($value, $len) {
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		return socket_write($this->socket, $value, $len);
	}

	public function read_unpack_u8()
	{
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		$ret = socket_read($this->socket, 1);
		if (FALSE === $ret) { 
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		
		$num = unpack("Cvalue", $ret);
		return $num["value"];
	}

	public function read_unpack_u16()
	{
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		$ret = socket_read($this->socket, 2);
		if (FALSE === $ret) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		
		$num = unpack("nvalue", $ret);
		return $num["value"];
	}

	public function read_unpack_u32()
	{
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		$ret = socket_read($this->socket, 4);
		if (FALSE === $ret) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		
		$num = unpack("Nvalue", $ret);
		$low = ((float)sprintf('%u', $num["value"]));
		return $low;
	}

	public function read_unpack_u64()
	{
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		$ret = socket_read($this->socket, 8);
		if (FALSE === $ret) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		
		$num = unpack("Nhigh/Nlow", $ret);
		$high = ((float)sprintf('%u', $num["high"]));
		$low = ((float)sprintf('%u', $num["low"]));
		return $high * 4294967296 + $low;
	}

	public function read_unpack($len)
	{
		if (FALSE === $this->connected) {
			\Util::log_warn(__METHOD__.' fail! '.($this->socket),2);
			return FALSE;
		}
		return socket_read($this->socket, $len);
	}

}
