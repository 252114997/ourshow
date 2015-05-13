<?php

namespace IPCService;

/**
 * 跟triton通信的基础类
 */
interface IPC 
{
	public function pack_write_u8($num);
	public function pack_write_u16($num);
	public function pack_write_u32($num);
	public function pack_write($value, $len);
	public function read_unpack_u8();
	public function read_unpack_u16();
	public function read_unpack_u32();
	public function read_unpack_u64();
	public function read_unpack($len);
}
