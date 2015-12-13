<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTbUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tb_users', function(Blueprint $table)
		{
			// $table->integer('id', true);
			$table->string('id')->primary();
			$table->string('username')->nullable();
			$table->integer('licence_count')->default(0); // 许可次数，指定次数内，可以免输入 邀请码
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tb_users');
	}

}
