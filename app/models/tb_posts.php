<?php

class tb_posts extends Eloquent {

	protected $table = 'tb_posts';
	
	public $timestamps = true;
	public $incrementing = true;
	protected $primaryKey = "id";
	// public $guarded = array('*');
	protected $fillable = array(
		'id', 
		'ablum_id', 
		'user_id',
		'text',
		'created_at', 
		'updated_at',
	);
}