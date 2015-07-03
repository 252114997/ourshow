<?php

class GlobalSetting extends Eloquent {

	protected $table = 'tb_global_settings';
	
	public $timestamps = false;
	public $incrementing = false;
	protected $primaryKey = "name";
	// public $guarded = array('*');
	protected $fillable = array('name', 'value');
}