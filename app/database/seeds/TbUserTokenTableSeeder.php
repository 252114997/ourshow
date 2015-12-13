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
				'user_id' => 'wanger',
				'token' => 'haha',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-06-07 11:01:28',
				'used' => 1,
			),
			1 => 
			array (
				'user_id' => 'zhaiyi',
				'token' => 'haha',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-06-07 11:19:40',
				'used' => 1,
			),
			2 => 
			array (
				'user_id' => 'wangyi',
				'token' => 'haha',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-12-13 21:31:29',
				'used' => 1,
			),
			3 => 
			array (
				'user_id' => '4',
				'token' => 'haha',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
				'used' => NULL,
			),
		));
	}

}
