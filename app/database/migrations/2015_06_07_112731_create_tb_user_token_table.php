<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTbUserTokenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tb_user_token', function(Blueprint $table)
		{
			$table->integer('user_id')->nullable();
			$table->string('token')->nullable();
			$table->timestamps();
			$table->boolean('used')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tb_user_token');
	}

}
