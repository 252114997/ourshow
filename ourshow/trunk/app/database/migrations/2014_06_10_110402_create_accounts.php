<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccounts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tb_account', function($t) {
			$t->increments('id');
			$t->string('username');
			$t->string('email');
			$t->string('bindip');
			$t->string('password', 64);
			$t->string('description')->nullable();
			$t->string('remember_token')->nullable();
			$t->integer('role_id')->unsigned();
			$t->tinyInteger('enable')->unsigned()->default(1);
			$t->timestamp('created_at');
			$t->timestamp('updated_at');
			$t->timestamp('login_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tb_account');
	}

}
