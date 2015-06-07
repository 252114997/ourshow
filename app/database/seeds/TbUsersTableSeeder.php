<?php

class TbUsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tb_users')->truncate();
        
		\DB::table('tb_users')->insert(array (
			0 => 
			array (
				'id' => 1,
				'username' => '王一',
				'token' => NULL,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			1 => 
			array (
				'id' => 2,
				'username' => '王二',
				'token' => NULL,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			2 => 
			array (
				'id' => 3,
				'username' => '翟一',
				'token' => NULL,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			3 => 
			array (
				'id' => 4,
				'username' => '翟二',
				'token' => NULL,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
		));
	}

}
