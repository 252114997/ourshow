<?php

namespace IPCService;

use IPCService\StatEntry;

/**
 * @brief 流量统计的数组存储，存储在StatArray里面的是StatEntry对象
 */
class StatArray 
{
	protected $sort_field = 'in_byte_rate';
	protected $sort_order = 'desc';

	// $a, $b为 StatEntry 对象
	public function compare($a, $b)
	{
		$field = $this->sort_field;
		$order = ($this->sort_order == 'desc') ? SORT_DESC : SORT_ASC;

		$result = static::compare_field($field, $a, $b);

		if ($order == SORT_ASC) {
			return $result;
		} else {
			return (-$result);
		}
	}

	public static function compare_field($field, $a, $b) {
		$a = (array) $a;
		$b = (array) $b;		
		$entry_a = isset($a[$field]) ? $a[$field] : 0;
		$entry_b = isset($b[$field]) ? $b[$field] : 0;
		return $entry_a - $entry_b;
	}
}

