<?php
namespace IPCService;

/**
 * @brief 应用状态信息
 */
class ApplicationStatEntry
{
	public $l7prot;
	public $l7name;
	public $stat; // StatEntry
	public $flow_count;

	public function __construct() {
		$this->stat = new StatEntry();
	}

	public function parse(&$ipc) {
		$this->l7prot = $ipc->read_unpack_u32();
		$this->l7name = self::translate($this->l7prot);
		$this->stat->parse($ipc);
		$this->flow_count = $ipc->read_unpack_u32();
	}

	static public function translate($l7prot) {
		$l7prot = intval($l7prot);
		$protocols = \CacheService::getProcotolCache();
		if ($protocols && array_key_exists($l7prot, $protocols)) {
			return $protocols[$l7prot]->description;
		} else {
			return 'mark:'.$l7prot;
		}
	}

	public function format() {
		return array_merge(
			$this->stat->format(),
			array(
				'l7prot' => $this->l7prot,
				'l7name' => $this->l7name,
				'flow_count' => $this->flow_count,
			)
		 );
	}
}