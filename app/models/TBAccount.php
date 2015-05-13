<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class TBAccount extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tb_account';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public $timestamps = true;
	public $incrementing = true;
	protected $primaryKey = "id";
	protected $fillable = array(
		"id"
		,"username"
		,"bindip"
		,"email"
		,"password"
		,"description"
		,"remember_token"
		,"role_id"
		,"enable"
		,'created_at'
		,'updated_at'
		,'login_at'
	);
}
