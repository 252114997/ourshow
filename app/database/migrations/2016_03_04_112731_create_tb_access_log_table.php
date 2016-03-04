<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTbAccessLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tb_access_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('client_ip')->nullable();
			$table->string('user_agent')->nullable();
			$table->string('request_url')->nullable();
			$table->text('extend_info')->nullable();
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
		Schema::drop('tb_access_log');
	}

}
