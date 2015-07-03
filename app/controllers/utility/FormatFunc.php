<?php

class FormatFunc {
	// 包速率
	static public function packetRate($value) {
		$_dot = Config::get('ui.decimal_digits', '2');
		$_format = Config::get("ui.rate_unit_format");

		return round($value, $_dot) + " pps";
	}
	// 速率
	static public function rate($value) {
		$_dot = Config::get('ui.decimal_digits', '2');
		$_format = Config::get("ui.rate_unit_format");

		if (!$_format || $_format === 'byte') {
			return self::byteRate($value, $_dot);
		} else if ($_format === 'bit') {
			return self::bitRate($value, $_dot);
		}
	}
	static public function bitRate($value, $dot) {
		$unit_array = [' bps', ' Kbps', ' Mbps', ' Gbps', ' Tbps'];
		return self::baseFormat($value*8, $unit_array, 1024, $dot);
	}
	static public function byteRate($value, $dot) {
		$unit_array = [' Bps', ' KBps', ' MBps', ' GBps', ' TBps'];
		return self::baseFormat($value, $unit_array, 1024, $dot);
	}

	// 大小
	static public function size($value) {
		$_dot = Config::get('ui.decimal_digits', '2');
		$_format = Config::get("ui.rate_unit_format");

		if (!$_format || $_format === 'byte') {
			return self::byteSize($value, $_dot);
		} else if ($_format === 'bit') {
			return self::bitSize($value, $_dot);
		}
	}
	static public function bitSize($value, $dot) {
		$unit_array = [' b', ' Kb', ' Mb', ' Gb', ' Tb'];
		return self::baseFormat($value*8, $unit_array, 1024, $dot);
	}
	static public function byteSize($value, $dot) {
		$unit_array = [' B', ' KB', ' MB', ' GB', ' TB'];
		return self::baseFormat($value, $unit_array, 1024, $dot);
	}

	// 数字
	static public function number($value) {
		$_dot = Config::get('ui.decimal_digits', '2');
		$_format = Config::get("ui.rate_unit_format");

		$unit_array = ['', ' 万', ' 亿'];
		return self::baseFormat($value, $unit_array, 10000, $_dot);
	}
	static public function baseFormat ($value, $unit_array, $unit, $dot) {
		if (null === $dot) {
			$dot = 2;
		}
		$i = 0;

		foreach ($unit_array as $i => $unit_value) {
			$i = intval($i);
			if ($value < pow($unit, $i+1)) {
				break;
			}
		}
		return round($value/(pow($unit, $i)), $dot).$unit_array[$i];
	}

	// 时间
	static public function time($value) {
		$days    = floor($value / (60*60*24));	
		$value   = $value % (60*60*24);
		$hours   = floor($value / (60*60));
		$value   = $value % (60*60);
		$minutes = floor($value / (60));
		$value   = $value % (60);
		$seconds = floor($value);
		
		$str = '';
		if ($days > 0)
			$str .= $days."天";
		if ($hours > 0)
			$str .= $hours."小时";
		if ($minutes > 0)
			$str .= $minutes."分";
		if ($seconds > 0)
			$str .= $seconds."秒";

		if ($str == '')
			$str .= "0 秒";
		
		return $str;
	}
}