<?php

return array(
	/**
	 * 全局默认的刷新间隔，每个模块的刷新时间如果没有指定
	 * 默认使用该刷新间隔
	 */
	'refresh_interval' => 5,

	/**
	 * 固定的刷新间隔
	 */
	'refresh_inverval_list' => '0,5,10,15,30',

	/**
	 * 首页应用排名的top N
	 */
	'dashboard_app_top_N'  => 10,

	/**
	 * 首页用户排名的top N
	 */
	'dashboard_usr_top_N'  => 10,


	/**
	 * 速率的显示格式，支持byte,bit
	 */
	'rate_unit_format' => 'byte',

	/**
	 * 数字后面的小数点保留位数
	 */
	'decimal_digits' => 2,
);