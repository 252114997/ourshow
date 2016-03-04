<?php

class tb_access_log extends Eloquent {

	protected $table = 'tb_access_log';
	
	public $timestamps = true;
	public $incrementing = true;
	protected $primaryKey = "id";
	// public $guarded = array('*');
	protected $fillable = array(
		'id', 
		'client_ip', 
		'user_agent',
		'extend_info',
		'request_url',
		'created_at', 
		'updated_at',
	);
}