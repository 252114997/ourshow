<?php

class tb_user_token extends Eloquent {

	protected $table = 'tb_user_token';
	
	public $timestamps = true;
	public $incrementing = true;
	protected $primaryKey = "";
	// public $guarded = array('*');
	protected $fillable = array(
		'user_id', 
		'used',
		'token',
		'created_at', 
		'updated_at',
	);
}