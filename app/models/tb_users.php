<?php

class tb_users extends Eloquent {

	protected $table = 'tb_users';
	
	public $timestamps = true;
	public $incrementing = true;
	protected $primaryKey = "id";
	// public $guarded = array('*');
	protected $fillable = array(
		'id', 
		'username', 
		'token',
		'created_at', 
		'updated_at',
	);
}