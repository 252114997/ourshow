<?php

class Util {

	static function log_fatal($msg,$skip_trace=1) {
		syslog(LOG_CRIT, (Util::get_trace($skip_trace)).$msg);
	}
	static function log_error($msg,$skip_trace=1) {
		if (strlen(Config::get('app.loglevel')) < 1) {
			return;
		}
		syslog(LOG_ERR, (Util::get_trace($skip_trace)).$msg);
	}
	static function log_warn($msg,$skip_trace=1) {
		if (strlen(Config::get('app.loglevel')) < 2) {
			return;
		}
		syslog(LOG_WARNING, (Util::get_trace($skip_trace)).$msg);
	}
	static function log_notice($msg,$skip_trace=1) {
		if (strlen(Config::get('app.loglevel')) < 3) {
			return;
		}
		syslog(LOG_NOTICE, (Util::get_trace($skip_trace)).$msg);
	}
	static function log_info($msg,$skip_trace=1) {
		if (strlen(Config::get('app.loglevel')) < 4) {
			return;
		}
		syslog(LOG_INFO, (Util::get_trace($skip_trace)).$msg);
	}
	static function log_debug($msg,$skip_trace=1) {
		if (strlen(Config::get('app.loglevel')) < 5) {
			return;
		}
		syslog(LOG_DEBUG, (Util::get_trace($skip_trace)).$msg);
	}
	static function get_trace($skip_trace=1, $level=1){
		// openlog('webadmin', LOG_PID, LOG_USER); // notice: 不添加此代码，默认 syslog 输出ident为 httpd ，且不显示进程ID
		$traces=debug_backtrace();
		for($count=0; $count<$skip_trace; $count++) {
			array_shift($traces);
		}

		$msg='';
		$count=0;
		foreach($traces as $trace) {
			if(isset($trace['file'],$trace['line'])) {
				$msg.="\nin ".$trace['file'].' ('.$trace['line'].') ';
				if(++$count>=$level) {
					break;
				}
			}
		}
		return $msg;
	}

	static public function getGlobalSet($param_name, $default_value = "") {
		$cur_setting = GlobalSetting::find($param_name);
		if (null == $cur_setting) {
			$cur_setting = GlobalSetting::updateOrCreate(
				array('name' => $param_name), 
				array('value' => $default_value)
			);
		}
		return $cur_setting->value;
	}
	static public function setGlobalSet($name, $value) {
		$cur_setting = GlobalSetting::updateOrCreate(
			array('name' => $name), 
			array('value' => $value)
		);
		return true;
	}

	static public function writeOperationLog($content, $detail_info)
	{
		$user_name = Auth::user()->username;
		$create_at = date('Y-m-d H:i:s');

		$values["user_name"] = $user_name;
		$values["content"] = $content;
		$values["create_at"] = $create_at;
		$values["detail_info"] = $detail_info;
		$insert_item = TBOperationLog::updateOrCreate(
			array('id' => 0), 
			$values
		);
		return ($insert_item != null);
	}
	static public function readKey($array, $key, $default_value = "") {
		return isset($array[$key]) ? $array[$key] : $default_value;
	}
	static public function gbkToUtf8($strInput) {
		return iconv('GBK','utf-8',$strInput);
	}
	static public function utf8ToGbk($strInput) {
		return iconv('utf-8','GBK',$strInput);
	}

	static public function safe_json_encode($value){
		if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
			$encoded = json_encode($value, JSON_PRETTY_PRINT);
		} else {
			$encoded = json_encode($value);
		}
		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				return $encoded;
			case JSON_ERROR_DEPTH:
				return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_STATE_MISMATCH:
				return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_CTRL_CHAR:
				return 'Unexpected control character found';
			case JSON_ERROR_SYNTAX:
				return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
			case JSON_ERROR_UTF8:
				$clean = Util::utf8ize($value);
				return Util::safe_json_encode($clean);
			default:
				return 'Unknown error'; // or trigger_error() or throw new Exception()

		}
	}

	static public function utf8ize($mixed) {
		if (is_array($mixed)) {
			foreach ($mixed as $key => $value) {
				$mixed[$key] = Util::utf8ize($value);
			}
		} else if (is_string ($mixed)) {
			return utf8_encode($mixed);
		}
		return $mixed;
	}

	static public function applyPolicy() {
		// TODO
		return false;
	}
	static public function applyIptables() {
		exec('perl /usr/local/WholetonTM/pl_sh/iptables.pl');
		return true;
	}
	static public function applyLoginportal() {
		exec('perl /usr/local/WholetonTM/pl_sh/loginportal.pl');
		return true;
	}
	static public function reloadTritonIpMac() {
		exec("/usr/local/WholetonTM/triton/bin/TritonIPCTools -r");
		return true;
	}
	static public function reloadTritonAcl() {
		exec("/usr/local/WholetonTM/triton/bin/TritonIPCTools -R");
		return true;
	}
	static public function reloadTritonAccess() {
		exec('/usr/local/WholetonTM/triton/bin/TritonIPCTools -I');
		return true;
	}
	static public function classifyLearning() {
		exec("/usr/local/WholetonTM/triton/bin/TritonIPCTools -l");
		return true;
	}
	static public function restartTriton() {
		exec("iptables -F -t mangle");
		exec("killall triton");
		exec("/usr/local/WholetonTM/triton/bin/triton -vvz");
		exec("perl /usr/local/WholetonTM/pl_sh/iptables.pl");
		return true;
	}
	static public function restartDevice() {
		//exec("/sbin/reboot"); // TODO 启用此代码
		return true;
	}
	static public function shutdownDevice() {
		//exec("shutdown -h now");// TODO 启用此代码
		return true;
	}
	static public function resetDevice() {
		 // exec("perl /usr/local/WholetonTM/pl_sh/default.pl");// TODO 启用此代码
		 return true;
	}
	static public function applyDhcpConfig() {
		system("/sbin/dhcpinfo");
		return true;
	}

	/**
	 * @brief 取出数组的指定分页
	 * @param $all_items
	 * @param $page 取出指定页数，从1开始
	 * @param $rows 每页行数
	 */
	static public function arrayPaged($all_items, $page, $rows) {
		$items = array();
		// page
		$all_pages = array_chunk($all_items, $rows, true);
		if (isset($all_pages[($page - 1)])) {
			$items = $all_pages[($page - 1)];
		}
		return $items;
	}

	static public function applyMonitorUser() {
		exec('perl /usr/local/WholetonTM/pl_sh/iptables.pl');
  		exec('/usr/local/WholetonTM/triton/bin/TritonIPCTools --update-pardon-user');
  		return true;
	}

	/**
	 * @brief 正则表达式 IPv4地址
	 *
	 * .'(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.'
	 * .'(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.'
	 * .'(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.'
	 * .'(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])'
	 */
	const PATTERN_IPADDR = '(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])';

	/**
	 * @brief 正则表达式 数值格式的IP掩码
	 */
	const PATTERN_NUM_MASK = '3[0-2]|[0-2]?[0-9]';

	/**
	 * @brief 正则表达式 网卡地址  01:02:03:04:05:06
	 */
	const PATTERN_MAC = '[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}:[A-Fa-f\d]{2}';


	/**
	 * @brief 验证 snmp server addr 格式的字符串变量
	 * @param in var：需要验证的字符串变量
	 *
	 * 支持 192.168.10.254/.1.3.6.1.2.1.3.1.1.2/public
	 * 支持多行
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateSnmpserveraddr($var) {
		// 从字符串头 开始匹配
		$cur_pattern = 
			'~^('
				.'('
					.self::PATTERN_IPADDR
				.')'               // 192.168.10.254
				.'[/]'           // /
				.'[\.\d]+'       // .1.3.6.1.2.1.3.1.1.2
				.'[/]'           // /
				.'[\w]+'           // public
				.'\n?'
			.')*$~'
		;

		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}

		return true;
	}

	/**
	 * @brief 验证 mac 格式的字符串变量
	 * @param in var：需要验证的字符串变量
	 * @param in mac: 返回IP
	 *
	 * 支持 01:02:03:04:05:06
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateMac($var, &$mac=null) {
		// 从字符串头 开始匹配
		$cur_pattern = '~^('.self::PATTERN_MAC.')$~';

		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}

		if (2 !== count($result)) {
			return false;
		}

		if (null !== $mac) {
			$mac = $result[1];
		}
		return true;
	}

	/**
	 * @brief 验证 ip,cid 格式的字符串变量
	 * @param in var：需要验证的字符串变量
	 * @param out ip: 返回ip
	 * @param out cid: 返回cid
	 *
	 * 支持 192.168.1.132,3c96b79172bde447d7fd54d3fac42a71
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateIpCid($var, &$ip=null, &$cid=null) {
		// 从字符串头 开始匹配
		$cur_pattern = '~^('.self::PATTERN_IPADDR.'),([\d\w]+)$~';

		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}
		if (3 !== count($result)) {
			return false;
		}

		if (null !== $ip) {
			$ip = $result[1];
		}
		if (null !== $cid) {
			$cid = $result[2];
		}
		return true;
	}

	/**
	 * @brief 验证 ip/mac 格式的字符串变量
	 * @param in var：需要验证的字符串变量
	 * @param out ip: 返回ip
	 * @param out mac: 返回mac
	 * @param in split_char: ip mac 中间的分隔符
	 * @param in end_char: ip mac 结尾字符
	 *
	 * 支持 192.168.1.1 [$split_char] 01:02:03:04:05:06 [$end_char]
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateIpMac($var, &$ip=null, &$mac=null, $split_char='/', $end_char='$') {
		// 从字符串头 开始匹配
		$cur_pattern = '~^('.self::PATTERN_IPADDR.')'.$split_char.'('.self::PATTERN_MAC.')'.$end_char.'~';

		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}
		if (3 !== count($result)) {
			return false;
		}

		if (null !== $ip) {
			$ip = $result[1];
		}
		if (null !== $mac) {
			$mac = $result[2];
		}
		return true;
	}

	/**
	 * @brief 验证 ip route add 命令格式的字符串
	 * @param in var：需要验证的字符串变量
	 * @param in ipmask: 返回 ipmask
	 * @param out gateway: 返回 gateway
	 *
	 * 支持 ip route add 192.168.81.0/255.255.255.0 via 192.168.1.180
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateStaticroutes($var, &$ipmask=null, &$gateway=null) {
		$cur_pattern = '~^ip route add '
							.'((?:'.self::PATTERN_IPADDR.')/(?:(?:'.self::PATTERN_IPADDR.')|(?:'.self::PATTERN_NUM_MASK.')))'
							.' via '
							.'('.self::PATTERN_IPADDR.')'
							.'$~';

		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}

		if (3 !== count($result)) {
			return false;
		}

		if (null !== $ipmask) {
			$ipmask = $result[1];
		}
		if (null !== $gateway) {
			$gateway = $result[2];
		}
		return true;
	}

	/**
	 * @brief 验证 ip addr add 与 vconfig add 命令格式的字符串
	 * @param in var：需要验证的字符串变量
	 * @param in ipmask: 返回 ipmask
	 * @param out gateway: 返回 gateway
	 *
	 * 支持下面两种格式的命令
	 * 'ip addr add 192.168.19.1/255.255.255.0 dev eth0';
	 * ‘/usr/local/WholetonTM/sbin/vconfig add eth0 2 && ifconfig eth0.2 192.168.19.1/255.255.255.0 up';
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateVlan($var, &$ipmask=null, &$network=null, &$vlanid=null, &$type=null) {
		
		// 生成正则表达式，以验证仅包括指定列表中 网卡名（eth0,eth1,br0） 的设备
		$dev_list = implode('|', array_merge(WorkmodeController::$BRIDGE_NAME_ARRAY, WorkmodeController::$ETH_NAME_ARRAY));
		
		$cur_vlan_pattern = '~^ip addr add '
							.'((?:'.self::PATTERN_IPADDR.')/(?:(?:'.self::PATTERN_IPADDR.')|(?:'.self::PATTERN_NUM_MASK.')))' // $ipmask
							.' dev '
							.'('.$dev_list.')' // $network = "";
							.'$~';
		$cur_trunk_pattern = '~^/usr/local/WholetonTM/sbin/vconfig add '
							.'('.$dev_list.')' // $network
							.' '
							.'(\d+)' // $vlanid
							.' && ifconfig '
							.'('.$dev_list.')' // $network_t
							.'\.'
							.'(\d+)' // $vlanid_t
							.' '
							.'((?:'.self::PATTERN_IPADDR.')/(?:(?:'.self::PATTERN_IPADDR.')|(?:'.self::PATTERN_NUM_MASK.')))' // $ipmask
							.' up'
							.'$~';

		if (1 === preg_match($cur_vlan_pattern, $var, $result)) {
			if (3 !== count($result)) {
				return false;
			}

			if (null !== $type) {
				$type = VlanController::VLAN_VLAN;
			}
			if (null !== $ipmask) {
				$ipmask = $result[1];
			}
			if (null !== $network) {
				$network = $result[2];
			}
			if (null !== $vlanid) {
				$vlanid = "";
			}
		}
		else if (1 === preg_match($cur_trunk_pattern, $var, $result)) {
			if (6 !== count($result)) {
				return false;
			}

			if (null !== $type) {
				$type = VlanController::VLAN_TRUNK;
			}
			if (null !== $network) {
				$network = $result[3];
			}
			if (null !== $vlanid) {
				$vlanid = $result[4];
			}
			if (null !== $ipmask) {
				$ipmask = $result[5];
			}
		}
		else {
			return false;
		}

		return true;
	}

	/**
	 * @brief 验证单行 整数 的字符串变量
	 * @param in var：需要验证的字符串变量
	 * @param in num: 返回num(string类型)
	 *
	 * 支持 123
	 * 支持 19-24
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateRangenum($var, &$num=null) {
		// 从字符串头 开始匹配
		$cur_pattern = '~^('
							.'(?:\d+-\d+)'
							.'|'
							.'(?:\d+)'
						.')$~';


		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}

		if (null !== $num) {
			$num = $result[0];
		}
		return true;
	}

	/**
	 * @brief 验证单行 ip 地址格式的字符串变量
	 * @param in var：需要验证的字符串变量
	 * @param in ip: 返回IP
	 *
	 * 仅支持 ipv4
	 * 支持 192.168.1.1
	 * 支持 192.168.1.1/24
	 * 支持 192.168.1.1/255.255.255.0
	 * 支持 192.168.1.1-192.168.1.33
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateIpAddr($var, &$ip=null) {
		// 从字符串头 开始匹配
		$cur_pattern = '~^('
							.'(?:(?:'.Util::PATTERN_IPADDR.')/(?:(?:'.self::PATTERN_IPADDR.')|(?:'.self::PATTERN_NUM_MASK.')))'
							.'|'
							.'(?:(?:'.self::PATTERN_IPADDR.')-(?:'.self::PATTERN_IPADDR.'))'
							.'|'
							.'(?:'.self::PATTERN_IPADDR.')'
						.')$~';


		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}
		// var_dump($var);
		// var_dump($result);

		if (null !== $ip) {
			$ip = $result[0];
		}
		return true;
	}

	/**
	 * @brief 获取网卡名称中的数值
	 * @param in var：需要验证的字符串变量
	 * @param in num: 返回num
	 *
	 * vaildateEthnum("eth23", $num); 执行成功后， $num 值为23
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateEthnum($var, &$num=null) {
		// 从字符串头 开始匹配
		$cur_pattern = '~^eth(\d)$~';

		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}

		if (2 !== count($result)) {
			return false;
		}

		if (null !== $num) {
			$num = intval($result[1]);
		}
		return true;
	}

	/**
	 * @brief 验证单个 ip 格式的字符串变量
	 * @param in var：需要验证的字符串变量
	 * @param in ip: 返回IP
	 *
	 * 仅支持 ipv4
	 * 仅支持 192.168.1.1
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateIp($var, &$ip=null) {
		// 从字符串头 开始匹配
		$cur_pattern = '~^('.self::PATTERN_IPADDR.')$~';

		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}

		if (2 !== count($result)) {
			return false;
		}

		if (null !== $ip) {
			$ip = $result[1];
		}
		return true;
	}

	/**
	 * @brief 验证单行 ip/mask 格式的字符串变量
	 * @param in var：需要验证的字符串变量
	 * @param in strict: =false掩码支持 ip 与 数值 格式  =true：严格匹配，仅支持192.168.1.1/255.255.255.0
	 * @param in ip: 返回IP
	 * @param out mask: 返回mask
	 *
	 * 仅支持 ipv4
	 * 支持 192.168.1.1/255.255.255.0
	 * 支持 192.168.1.1/24
	 * 
	 * @return true:验证成功  false:验证失败
	 */
	static public function vaildateIpMask($var, $strict, &$ip=null, &$mask=null) {
		// 从字符串头 开始匹配
		if (false === $strict) {
			$cur_pattern = '~^('.self::PATTERN_IPADDR.')/((?:'.self::PATTERN_IPADDR.')|(?:'.self::PATTERN_NUM_MASK.'))$~';
		}
		else {
			$cur_pattern = '~^('.self::PATTERN_IPADDR.')/('.self::PATTERN_IPADDR.')$~';
		}
		// 仅返回第一次匹配结果 preg_match()
		if (1 !== preg_match($cur_pattern, $var, $result)) {
			return false;
		}
		if (3 !== count($result)) {
			return false;
		}

		if (null !== $ip) {
			$ip = $result[1];
		}
		if (null !== $mask) {
			$mask = $result[2];
		}
		return true;
	}

	static public function vaildateUrl($var, $url = null) {
		if ( $parts = parse_url($var) ) {
			if ( !isset($parts["scheme"]) )
			{
				$var = "http://$var";
			}
		}
		if (false === filter_var($var, FILTER_VALIDATE_URL)) {
			return false;
		}
		if (null !== $url) {
			$url = $var;
		}
		return true;
	}

	/**
	 * @brief convert cidr mask (ex: /28) to netmask (ex: 255.255.255.240)
	 *
	 * 将数字掩码转换为字符串掩码
	 * 24 -> 255.255.255.0
	 */
	static public function cidr2mask($num_mask) {
		$right_bit = 32-intval($num_mask);
		return long2ip( ( ((~0)>>($right_bit)) << ($right_bit) ) );
	}

	/**
	 * @brief convert a netmask (ex: 255.255.255.240) to a cidr mask (ex: /28):
	 *
	 * xor-ing will give you the inverse mask,
	 * log base 2 of that +1 will return the number
	 * of bits that are off in the mask and subtracting
	 * from 32 gets you the cidr notation 
	 */
	static public function mask2cidr($str_mask){
		$long = ip2long($str_mask);
		$base = ip2long('255.255.255.255');
		return 32-log(($long ^ $base)+1,2);
	}

	/**
	 * @brief IP地址转换为无符号整数
	 *
	 * @return unsigned long
	 */
	static public function ip2unsignedlong($ip_str) {
		return (float)sprintf("%u", ip2long($ip_str));
	}

	/**
	 * @brief 过滤 & ; 等字符。一般用于检查执行命令串的安全性。（含 & ; 的字符可进行攻击）
	 *
	 * @return 过滤后的字符串
	 */
	static public function urlLoophole($str){
		return preg_replace("/(%26)|(;)|(&)/i", "", $str);
	}

	/**
	 * @brief 安全性检查
	 *
	 * 删除 非 数字(\d) 冒号(:) 横线(-) 的字符
	 * @return 过滤后的字符串
	 */
	static public function replaceNotDatetime($date_time_str) {
		return preg_replace("/[^\d-: ]/", "", $date_time_str);
	}

	/**
	 * @brief 安全性检查
	 *
	 * 删除 非 数字(\d) 正号(:) 负号(-) 的字符
	 * @return 过滤后的字符串
	 */
	static public function replaceNotNumber($number_str) {
		return preg_replace("/[^\d-+]/", "", $number_str);
	}

	/**
	 * @brief 安全性检查
	 *
	 * 删除 非 数字(\d) 点号(:) 的字符
	 * @return 过滤后的字符串
	 */
	static public function replaceNotIpstring($string) {
		return preg_replace("/[^\d\.]/", "", $string);
	}

	/**
	 * @brief 根据数组键名过滤数据元素
	 *
	 * 返回过滤后的数组
	 * @return array
	 */
	static public function filterByKey($array, $match_str) {
		$func = function($value) use(&$array, $match_str) {
			$key = key($array);
			next($array);
			if (0 === strpos($key, $match_str)) {
				return true;
			}
			return false;
		};

		$filter_array = array_filter(
			$array, 
			$func
		);

		return $filter_array;
	}

	/**
	* @brief 强制删除目录及其下属文件，如果存在子目录，会递归删除
	*/
	public static function delForceDir($dir) {
		if (!is_dir($dir)) {
			return unlink($dir);
		}
		$files = array_diff(scandir($dir), array('.','..')); 
		foreach ($files as $file) { 
			$full_path = "$dir/$file";
			(is_dir($full_path)) ? self::delForceDir($full_path) : unlink($full_path); 
		}
		return rmdir($dir); 
	}

	/**
	* @brief 强制写入文件，如果目录不存在，会递归创建
	*/
	static public function fileForceContents($dir, $contents){
		$parts = explode('/', $dir);
		$file = array_pop($parts);
		$dir = '';
		foreach($parts as $part) {
			if(!is_dir($dir .= "/$part")) {
				mkdir($dir);
			}
		}
		return file_put_contents("$dir/$file", $contents);
	}

	/**
	 * @brief Data URI scheme
	 */
	static public function getDataURI($image, $mime = '') {
		return 
			'data: '
			.(function_exists('mime_content_type') ? mime_content_type($image) : $mime)
			.';base64,'
			.base64_encode(file_get_contents($image));
	}
}

class UtilController extends BaseController {
	/**
	 * 将旧的协议表转换成新的协议表
	 */
	public function protocolTableConvert() {
		$data1 = DB::table('protocolclassroot')->get();

		foreach ($data1 as $d) {
			echo "array('id' => $d->ClassRootID, 'pid' => -1, 'level' => 0, 'name'=> '', 'description' => '$d->Description'),";
			echo "<br>";
		}

		$data2 = DB::table('protocolclass')->whereRaw('ClassID <> ClassRootID')->get();

		foreach ($data2 as $d) {
			echo "array('id' => $d->ClassID, 'pid' => $d->ClassRootID, 'level' => 1, 'name'=> '', 'description' => '$d->Description'),";
			echo "<br>";
		}

		$data3 = DB::table('baseprotocol')->whereRaw('ProtocolID <> ClassID')->get();

		foreach ($data3 as $d) {
			echo "array('id' => $d->ProtocolID, 'pid' => $d->ClassID, 'level' => 2, 'name'=> '$d->ProtocolName', 'description' => '$d->Description'),";
			echo "<br>";
		}
	}

	public function applyPolicy() {
		if (!Util::applyPolicy()) {
			$data = array();
			$data['status'] = 0;
			$data['msg'] = "应用生效失败";
			return Response::json($data);
		}

		$data = array();
		$data['status'] = 1;
		$data['msg'] = "success";
		return Response::json($data);
	}

	/**
	 * @brief 扫描指定IP段
	 *
	 * 返回扫描后的数组
	 * @return array
	 */
	public function scanip() {
		$input_json = Input::json();
		$net_addr = $input_json->get("net_addr");

		$ipmask = "";
		if (false === Util::vaildateIpAddr($net_addr, $ipmask) ) {
			return Response::json(array("status" => 0, "msg" => "格式不正确"));
		}

		$ip = "";
		$mask = "";
		if (true === Util::vaildateIpMask($ipmask, true, $ip, $mask)) {
			// 将 192.168.1.1/255.255.255.0 格式 转换为 192.168.1.1/24 的格式
			// 因为 arp-scan 仅支持 cidr 格式掩码
			$mask = Util::mask2cidr($mask);
			$ipmask = "{$ip}/{$mask}";
		}

		// 动态获取接口列表
		$interface_list = WorkmodeController::getCurDevlist();

		// 获取扫描结果
		$out_string = array();
		foreach ($interface_list as $interface) {
			$cmd_str = "/usr/local/WholetonTM/sbin/arp-scan --interface={$interface} {$ipmask}";
			exec($cmd_str, $out_string);
		}

		$result_array = array();
		$ipmac_map = array();
		foreach ($out_string as $line_string) {
			$ip = "";
			$mac = "";
			// TODO get pc name
			if (false === Util::vaildateIpMac($line_string, $ip, $mac, '\s', '\s')) {
				continue;
			}
			$map_key = $ip."/".$mac;
			if (isset($ipmac_map[$map_key])) {
				continue;
			}
			$ipmac_map[$map_key] = 1;

			$result_array[] = array("ip" => $ip, "mac" => $mac);
		}

		return Response::json(array(
			"status" => 1, 
			"msg" => "success",
			"data" => $result_array
		));
	}
}