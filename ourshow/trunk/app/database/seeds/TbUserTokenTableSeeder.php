<?php

class TbUserTokenTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tb_user_token')->truncate();
        
		\DB::table('tb_user_token')->insert(array (
			0 => 
			array (
				'user_id' => 2,
				'token' => 'haha',
				'created_at' => NULL,
				'updated_at' => '2015-06-07 11:01:28',
				'used' => 1,
			),
			1 => 
			array (
				'user_id' => 3,
				'token' => 'haha',
				'created_at' => NULL,
				'updated_at' => '2015-06-07 11:19:40',
				'used' => 1,
			),
			2 => 
			array (
				'user_id' => 1,
				'token' => 'haha',
				'created_at' => NULL,
				'updated_at' => NULL,
				'used' => NULL,
			),
			3 => 
			array (
				'user_id' => 4,
				'token' => 'haha',
				'created_at' => NULL,
				'updated_at' => NULL,
				'used' => NULL,
			),
		));
	}

}
