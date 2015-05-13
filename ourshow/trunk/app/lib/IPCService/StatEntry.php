<?php

namespace IPCService;

/**
 * @brief 流量属性
 */
class StatEntry 
{
	public $out_pkt_rate = '';
	public $in_pkt_rate = '';
	//public $total_pkt_rate = '';
	
	public $out_byte_rate = '';
	public $in_byte_rate = '';
	//public $total_pkt_sum = '';
	
	public $out_byte_sum = '';
	public $in_byte_sum = '';
	//public $total_byte_sum = '';

	public function parse(&$ipc) {
		$this->out_pkt_rate = $ipc->read_unpack_u64();
		$this->in_pkt_rate = $ipc->read_unpack_u64();
		//$this->total_pkt_rate = $this->out_pkt_rate + $this->in_pkt_rate;

		$this->out_byte_rate = $ipc->read_unpack_u64();
		$this->in_byte_rate = $ipc->read_unpack_u64();
		//$this->total_byte_rate = $this->out_byte_rate + $this->in_byte_rate;

		$this->out_pkt_sum = $ipc->read_unpack_u64();
		$this->in_pkt_sum = $ipc->read_unpack_u64();
		//$this->total_pkt_sum = $this->out_pkt_sum + $this->in_pkt_sum;

		$this->out_byte_sum = $ipc->read_unpack_u64();
		$this->in_byte_sum = $ipc->read_unpack_u64();
		//$this->total_byte_sum = $this->out_byte_sum + $this->in_byte_sum;
	}
	public function add($other) {
		$this->out_pkt_rate += $other->out_pkt_rate;
		$this->in_pkt_rate += $other->in_pkt_rate;
		//$this->total_pkt_rate = $this->out_pkt_rate + $this->in_pkt_rate;

		$this->out_byte_rate += $other->out_byte_rate;
		$this->in_byte_rate += $other->in_byte_rate;
		//$this->total_byte_rate = $this->out_byte_rate + $this->in_byte_rate;

		$this->out_pkt_sum += $other->out_pkt_sum;
		$this->in_pkt_sum += $other->in_pkt_sum;
		//$this->total_pkt_sum = $this->out_pkt_sum + $this->in_pkt_sum;

		$this->out_byte_sum += $other->out_byte_sum;
		$this->in_byte_sum += $other->in_byte_sum;
		//$this->total_byte_sum = $this->out_byte_sum + $this->in_byte_sum;
	}
	public function format() {
		return array(
			'out_pkt_rate' => ($this->out_pkt_rate),
			'in_pkt_rate' => ($this->in_pkt_rate),
			//'total_pkt_rate' => ($this->total_pkt_rate),

			'out_byte_rate' => $this->out_byte_rate,
			'in_byte_rate' => $this->in_byte_rate,
			//'total_byte_rate' => $this->total_byte_rate,

			'out_pkt_sum' => $this->out_pkt_sum,
			'in_pkt_sum' => $this->in_pkt_sum,
			//'total_pkt_sum' => $this->total_pkt_sum,

			'out_byte_sum' => $this->out_byte_sum,
			'in_byte_sum' => $this->in_byte_sum,
			//'total_byte_sum' => $this->total_byte_sum,
		);
	}
}