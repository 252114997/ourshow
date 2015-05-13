<?php
/**
 * @brief  端口映射 IP映射 的配置文件
 */
class FirewallConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = '/etc/sysconfig/firewall';
	static public $split_charactor = "=";

	static public function loadAllConfigMachine() {
		$all_config = parent::loadAllConfig();
		return self::parseConfigMachine($all_config);
	}
	static public function saveConfigMachine($new_config_array, $override = false) {
		$all_config         = parent::loadAllConfig();
		$all_config_ip      = self::parseConfigIP($all_config);
		$all_config_machine = self::parseConfigMachine($all_config);
		if ($override) {
			$all_config_machine = $new_config_array;
		}
		else {
			$all_config_machine = ($new_config_array + $all_config_machine);
		}

		return parent::saveConfig(
			self::restoreConfigIP($all_config_ip) 
			+ self::restoreConfigMachine($all_config_machine)
			, true
		);
	}

	static public function loadAllConfigIP() {
		$all_config = parent::loadAllConfig();
		return self::parseConfigIP($all_config);
	}

	static public function saveConfigIP($new_config_array, $override = false) {
		$all_config         = parent::loadAllConfig();
		$all_config_ip      = self::parseConfigIP($all_config);
		$all_config_machine = self::parseConfigMachine($all_config);
		if ($override) {
			$all_config_ip = $new_config_array;
		}
		else {
			$all_config_ip = ($new_config_array + $all_config_ip);
		}

		return parent::saveConfig(
			self::restoreConfigIP($all_config_ip) 
			+ self::restoreConfigMachine($all_config_machine)
			, true
		);
	}
	/**
	 * @brief 解析配置文件
	 */
	static public function parseConfigMachine($config_values) {
		$format_config_values = array();
		foreach ($config_values as $key => $value) {
			if (false === strpos($key, 'virtualmachine')) {
				continue;
			}
			$config = array();
			if (preg_match("/^(\#)?virtualmachine(.*)$/", $key, $match_values)>=1) {
				$config['status'] = ('#'==$match_values[1]) ? 0 : 1; // 0禁用（#）  1启用（）
				$config['id']     = intval($match_values[2]);
			}
			$split_array = explode(':', $value);
			if (count($split_array) >= 6) {
				$ports     = explode('-', $split_array[0]); 
				$map_ports = explode('-', $split_array[1]);
				$config['lport']        = $ports[0];
				$config['rport']        = $ports[1] ;
				$config['map_lport']    = $map_ports[0];
				$config['map_rport']    = $map_ports[1];
				$config['map_server']   = explode(',', $split_array[2]);
				$config['server']       = $split_array[3];
				$config['protocol']     = $split_array[4];
				$config['name']         = $split_array[5];
			}

			$format_config_values[$config['id']] = $config;
		}
		return $format_config_values;
	}

	/**
	 * @brief 格式化数据
	 */
	static public function restoreConfigMachine($save_data){
		$save_list = array();
		foreach ($save_data as $key => $value){
			//数据格式化
			$keys   = '';
			$values = '';
			foreach ($value['map_server'] as $item_key => $item_value) {
				if (trim($item_value) == '') {
					unset($value['map_server'][$item_key]);
				}
			}
			//格式化
			$values = $value['lport'].'-'.$value['rport']
			.':'.$value['map_lport'].'-'.$value['map_rport']
			.':'.implode(',', $value['map_server'])
			.':'.$value['server']
			.':'.$value['protocol']
			.':'.$value['name'];
			$keys = ('0'== $value['status'] ? '#' : '')."virtualmachine".$value['id'];
			$save_list[$keys] = $values;
		}
		return $save_list;
	}

	/**
	 * @brief 解析配置文件   virtualip2 = 1.1.1.1 : 11.11.11.11 , 22.22.22.22
	 */
	static public function parseConfigIP($config_values) {
		$format_ipconfig_values = array();
		foreach ($config_values as $key => $value) {
			if (false === strpos($key, 'virtualip')) {
				continue;
			}
			$config = array();
			if (preg_match("/^virtualip(.*)$/", $key, $match_values)>=1) {
				$config['id'] = intval($match_values[1]);
			}
			$split_array = explode(':', $value);
			if (count($split_array) >= 2) {
				$config['serverIP']     = $split_array[0];
				$config['ipmap_server'] = explode(',', $split_array[1]);
			}
			$format_ipconfig_values[$config['id']] = $config;
		}
		return $format_ipconfig_values;
	}

	/**
	 * @brief 格式化数据
	 */
	static public function restoreConfigIP($saveIP_data){
		$save_list = array();
		foreach ($saveIP_data as $key => $value){
			//数据格式化
			$keys   = '';
			$values = '';
			//map_server 为空的处理
			foreach ($value['ipmap_server'] as $item_key => $item_value) {
				if (trim($item_value) == '') {
					unset($value['ipmap_server'][$item_key]);
				}
			}
			//格式化
			$values = $value['serverIP'].':'.implode(',', $value['ipmap_server']);
			$keys   = "virtualip".$value['id'];
			$save_list[$keys] = $values;
		}
		return $save_list;
	}
}

/**
 * @brief ftpcmd记录文件
 *
 * /opt/triton/ftp/192.168.1.74.ftp
 */
class FtpCmdConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_LINENO;
	static public $filepath = 'TODO set it';
	static public $split_charactor = "";
}

/**
 * @brief Url库版本
 */
class UrlConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = '/usr/local/WholetonTM/triton/conf/URL/url.conf';
	static public $split_charactor = "=";
}

/**
 * @brief 产品ID
 */
class ProductCodeConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_LINENO;
	static public $filepath = '/etc/socks5/code';
	static public $split_charactor = "";
}

/**
 * @brief 保存系统总带宽
 */
class BandwidthConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/bandwidth";
	static public $split_charactor = "=";
}

/**
 * @brief 管理界面版本
 */
class WebUIversionConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/usr/local/WholetonTM/htdocs/base/VERSION";
	static public $split_charactor = "=";
}

/**
 * @brief 系统版本相关信息配置
 */
class SysversionConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/sysconfig/sys_version";
	static public $split_charactor = "=";
}

/**
 * @brief 系统型号相关信息配置
 */
class WholetonModelConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/sysconfig/wholeton_model";
	static public $split_charactor = "=";
}

/**
 * @brief 更改HA的配置
 */
class HaConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/sysconfig/ha";
	static public $split_charactor = "=";
}

/**
 * @brief 更改时间同步服务器的配置
 */
class NtpConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/ntp.conf";
	static public $split_charactor = " ";
}

/**
 * @brief 更改主机名称的配置
 */
class NetworkConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/sysconfig/network";
	static public $split_charactor = "=";
}

/**
 * @brief 发件服务器的配置
 */
class MailServerConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/sendmail.conf";
	static public $split_charactor = "=";
}

/**
 * @brief 3G上网卡的配置
 */
class MobilenetworkConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/ppp/3G.conf";
	static public $split_charactor = "=";
}


/**
 * @brief 伪IP防护 的配置
 */
class AntifakeipConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/sysconfig/fake_ip";
	static public $split_charactor = "\t";
}

/**
 * @brief SNMP客户端的配置
 */
class SnmpClientConfig extends BaseConfigFile {
	static public $key_type = parent::KEY_TYPE_LINENO;
	static public $filepath = "/etc/sysconfig/snmp_client.conf";
	static public $split_charactor = "";
}

/**
 * @brief 读取 dhcpd.leases 的配置
 * 
 */
class DhcpLeasesConfig {
	/**
	 * @brief 加载所有配置
	 * @return 以数组类型返回所有配置
	 */
	static public function loadAllConfig() {
		$open_file = fopen("/var/state/dhcp/dhcpd.leases", "r");
		if (false === $open_file) {
			return false;
		}

		$config_array = array();
		if ($open_file)
		{
			//Call the dhcplease file parser
			$parser = new ParserDhcpdLeasesClass();
			$config_array = $parser->parser($open_file);
		}
		fclose($open_file);
		return $config_array;
	}

}

/**
 * @brief 保存 dhcp 的配置文件
 * 
 */
class DhcpConfig extends BaseConfigFile
{
	static public $key_type = parent::KEY_TYPE_SINGLE;
	static public $filepath = "/etc/sysconfig/dhcp";
	static public $split_charactor = "=";
}

/**
 * @brief 保存vlan的配置文件
 * 
 */
class VlanConfig extends BaseConfigFile
{
	static public $key_type = parent::KEY_TYPE_LINENO;
	static public $filepath = "/etc/sysconfig/vlan";
	static public $split_charactor = "";
}

/**
 * @brief 保存静态路由的配置文件
 * 
 * 文件保存 ip route 命令，无 键/值 的概念，所以 $split_charactor 为空字符
 * 示例：
 * ip route add 192.168.81.0/24 via 192.168.81.1
 * ip route add 192.168.82.0/24 via 192.168.1.180
 */
class StaticroutesConfig extends BaseConfigFile
{
	static public $key_type = parent::KEY_TYPE_LINENO;
	static public $filepath = "/etc/sysconfig/staticroutes";
	static public $split_charactor = "";
}

/**
 * @brief 保存DNS
 *
 * 表示允许同一键名，拥有多个值，如下所示
 * nameserver 114.114.114.114
 * nameserver 202.106.46.151
 * nameserver 8.8.4.4
 */
class DnsConfig extends BaseConfigFile
{
	static public $key_type = parent::KEY_TYPE_MULTI;
	static public $filepath = "/etc/resolv.conf";
	static public $split_charactor = " ";
}

class PppsecretConfig extends BaseConfigFile
{
	static public $filepath = "/etc/ppp/pap-secrets";
	static public $split_charactor = "\t*\t";
}

class Mobile3gConfig extends BaseConfigFile
{
	static public $filepath = "/etc/ppp/3G.conf";
	static public $split_charactor = "=";
}

class PppoeConfig extends BaseConfigFile
{
	static public $filepath = "/etc/ppp/pppoe.conf";
	static public $split_charactor = "=";
}

class NetConfig extends BaseConfigFile
{
	static public $filepath = "/etc/net.conf";
	static public $split_charactor = "=";
}

class FirewallsetConfig extends BaseConfigFile
{
	static public $filepath = "/etc/sysconfig/firewallset";
	static public $split_charactor = "=";
}

class RadiusclientConfig extends BaseConfigFile
{
	static public $filepath = "/etc/sysconfig/radiusclient.conf";
	static public $split_charactor = "\t";
}

class ActiveDirectoryConfig extends BaseConfigFile
{
	static public $filepath = "/etc/sysconfig/activeD.cfg";
	static public $split_charactor = "=";
}

class LdapConfig extends BaseConfigFile
{
	static public $filepath = "/etc/sysconfig/ldap.cfg";
	static public $split_charactor = "=";
}

class SmsConfig extends BaseConfigFile
{
	static public $filepath = "/etc/sysconfig/sms_account.txt";
	static public $split_charactor = "=";
}

class IpMacConfig extends BaseConfigFile {
	// TODO 优化这个文件读取性能，因为在 active user 中调用十分频繁
	// 可先读取文件最后修改时间，决定是否重复读文件
	static public $filepath = "/etc/ComputerNameMACList";
	static public $split_charactor = "=__";
}

/**
 * @brief 免监控IP配置
 */
class FreeMonitorIPConfig extends BaseConfigFile
{
	static public $filepath = "/etc/sysconfig/pardonIP";
	static public $split_charactor = "\t";
}

/**
 * @brief 免监控应用设置 
 */
class FreeMonitorQQConfig extends BaseConfigFile
{
	static public $filepath = '/usr/local/WholetonTM/triton/conf/qqpermit.conf';
	static public $split_charactor = ",";
}

class FreeMonitorMSNConfig extends BaseConfigFile
{
	static public $filepath = '/usr/local/WholetonTM/triton/conf/msnpermit.conf';
	static public $split_charactor = ",";
}

class FreeMonitorFETIONConfig extends BaseConfigFile
{
	static public $filepath = '/usr/local/WholetonTM/triton/conf/ftpermit.conf';
	static public $split_charactor = ",";
}

class FreeMonitorMAILConfig extends BaseConfigFile
{
	static public $filepath = '/usr/local/WholetonTM/triton/conf/mail_permit.conf';
	static public $split_charactor = ",";
}

class FreeMonitorBBSConfig extends BaseConfigFile
{
	static public $filepath = '/usr/local/WholetonTM/triton/conf/bbs_permit.conf';
	static public $split_charactor = ",";
}

class FreeMonitorWEBConfig extends BaseConfigFile
{
	static public $filepath = '/usr/local/WholetonTM/triton/conf/webtitle_permit.conf';
	static public $split_charactor = ",";
}

class BaseConfigFile
{
	static public $filepath = "TODO override";
	static public $split_charactor = "TODO override";

	/**
	 * @brief 指示配置文件中 键 类型的选项
	 */
	const KEY_TYPE_SINGLE = 1; // 一个键 对应 一个值  (不允许键重复)
	const KEY_TYPE_MULTI  = 2; // 一个键 对应 多个值  (允许键重复)
	const KEY_TYPE_LINENO = 3; // 以文件行号为键,行号从 1 开始
	static public $key_type = self::KEY_TYPE_SINGLE;

	/**
	 * @brief 配置项中，仅有键，没有值（值为 null 或 ''）时，是否在键后追加 分隔符
	 *
	 * true:追加分隔符  false:不追加
	 */
	static public $keep_split_charactor = true;

	/**
	 * @brief 读取一个键
	 * @param key:键名称，即参数名称
	 * @return 返回键对应的值 
	 */
	static public function readKey($key, $default_value = null) {
		$all_config = static::loadAllConfig();
		if (isset($all_config[$key])) {
			return $all_config[$key];
		}
		else {
			if (self::KEY_TYPE_MULTI == static::$key_type) {
				return (null === $default_value) ? array() : $default_value;
			}
			else if (self::KEY_TYPE_LINENO == static::$key_type) {
				return (null === $default_value) ? "" : $default_value;
			}
			else {
				return (null === $default_value) ? "" : $default_value;
			}
		}
	}
	
	/**
	 * @brief 保存一组键值对
	 * @param  $new_config_array 数组格式的键值对，类似 array("key1 => value1", "key2 => value2") 这样的格式
	 * @param  $override         false:不覆盖文件原有内容（合并）  true:以新配置覆盖文件内容
	 * @return bool              false:保存失败;                   true:保存成功返回文件总长
	 *
	 * 为了不读取到旧数据，这里将 read() write() 操作都包含在加锁解锁之间，使读写保持在一个原子操作中。
	 * 在虚拟机中测试表明，执行100次本函数大约需要1秒钟
	 * 建议在需要保存的配置参数较多时，一次性放入new_config_array中写入文件，以提高性能
	 */
	static public function saveConfig($new_config_array, $override = false) {
		$fp = fopen(static::$filepath, 'c+');
		if (false === $fp) {
			return false;
		}
		flock($fp,LOCK_EX);

		// read
		if ($override) {
			$all_config = $new_config_array;
		}
		else {
			$file_content = file_get_contents(static::$filepath);
			$all_config = ($new_config_array + static::contentToArray($file_content));
		}

		$file_content = static::arrayToContent($all_config);

		// write
		ftruncate($fp, 0);
		$res = fwrite($fp, $file_content);

		flock($fp,LOCK_UN);
		fclose($fp);

		Util::writeOperationLog(
				"修改 ".static::$filepath." "
				, " ".json_encode($new_config_array)
		);

		return $res;
	}

	/**
	 * @brief 加载所有配置
	 * @return 以数组类型返回所有配置
	 */
	static public function loadAllConfig() {
		$fp = fopen(static::$filepath,'c+');
		if (false === $fp) {
			return false;
		}

		flock($fp, LOCK_EX); // blocked function
		$file_content = file_get_contents(static::$filepath);
		flock($fp,LOCK_UN);
		fclose($fp);

		return static::contentToArray($file_content);
	}

	/**
	 * @brief 将文件内容解析为数组
	 * @return 含有所有参数配置的数组
	 */
	static protected function contentToArray($file_content) {
		$all_config = array();
		if ("" == $file_content) {
			return $all_config;
		}
		$index = 1;
		foreach (explode("\n", $file_content) as $line_string) {
			if ("" == static::$split_charactor) {
				$key_value = array(@trim($line_string,"\r\n"));
			}
			else {
				$key_value = explode(static::$split_charactor, @trim($line_string,"\r\n"), 2); // 使用 trim() 将换行符去掉
			}
			$key   = isset($key_value[0]) ? $key_value[0] : "";
			$value = isset($key_value[1]) ? $key_value[1] : "";

			if (self::KEY_TYPE_MULTI == static::$key_type) {
				if (!isset($all_config[$key])) {
					$all_config[$key] = array();
				}
				$all_config[$key][] = $value; 
			}
			else if (self::KEY_TYPE_LINENO == static::$key_type) {
				$all_config[$index++] = $key;
			}
			else {
				$all_config[$key] = $value;				
			}
		}

		return $all_config;
	}

	/**
	 * @brief 将数组解析为文件内容
	 * @return 文件内容
	 */
	static protected function arrayToContent($config_array) {
		$content_array = array();
		foreach ($config_array as $key => $value) {

			if (self::KEY_TYPE_MULTI == static::$key_type) {
				foreach($value as $item_value) {
					$content_item = "";
					$content_item .= $key;
					if (null !== $item_value && "" !== $item_value) {
						$content_item .= (static::$split_charactor.$item_value); // item_value 为空时，不在行尾添加 split_charactor 
					}
					$content_array[] = $content_item;
				}
			}
			else if (self::KEY_TYPE_LINENO == static::$key_type) {
				$content_array[] = $value;
			}
			else {
				$content_item = "";
				$content_item .= $key;
				if (true == static::$keep_split_charactor) {
					$content_item .= (static::$split_charactor.$value); // 即使 value 为空时，也在行尾添加 split_charactor			
				}
				else {
					if (null !== $value && "" !== $value) {
						$content_item .= (static::$split_charactor.$value); // value 为空时，不在行尾添加 split_charactor 
					}
				}
				$content_array[] = $content_item;
			}
		}

		return implode("\n", $content_array);
	}
}
