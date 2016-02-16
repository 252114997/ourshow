<?php

class tb_ablum_picture extends Eloquent {

	protected $table = 'tb_ablum_picture';
	
	public $timestamps = true;
	public $incrementing = false;
	// protected $primaryKey = "";
	// public $guarded = array('*');
	protected $fillable = array(
		'ablum_id', 
		'picture_id', 
		'created_at', 
		'updated_at',
	);
}