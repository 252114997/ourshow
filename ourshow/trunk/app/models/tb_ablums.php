<?php

class tb_ablums extends Eloquent {

	protected $table = 'tb_ablums';
	
	public $timestamps = true;
	public $incrementing = true;
	protected $primaryKey = "id";
	// public $guarded = array('*');
	protected $fillable = array(
		'id', 
		'title', 
		'caption',
		'picture_id',
		'created_at', 
		'updated_at',
	);
}