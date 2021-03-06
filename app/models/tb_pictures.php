<?php

class tb_pictures extends Eloquent {

	protected $table = 'tb_pictures';
	
	public $timestamps = true;
	public $incrementing = false;
	protected $primaryKey = "id";
	// public $guarded = array('*');
	protected $fillable = array(
		'id', 
		'name', 
		'path',
		'caption',
		'created_at', 
		'updated_at',
	);
}