<?php

class tb_like extends Eloquent {

	protected $table = 'tb_like';
	
	public $timestamps = true;
	public $incrementing = true;
	protected $primaryKey = "id";
	// public $guarded = array('*');
	protected $fillable = array(
		'id', 
		'ablum_id', 
		'user_id',
		'created_at', 
		'updated_at',
	);
}