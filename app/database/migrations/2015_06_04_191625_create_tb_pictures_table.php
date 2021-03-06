<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTbPicturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tb_pictures', function(Blueprint $table)
		{
			// $table->integer('id', true);
			$table->string('id')->primary();
			$table->string('name')->nullable();
			$table->string('path')->nullable();
			$table->string('caption')->nullable();
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
		Schema::drop('tb_pictures');
	}

}
