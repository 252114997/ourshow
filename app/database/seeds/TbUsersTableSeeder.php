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
				'id' => 'wanger',
				'username' => '王二',
				'licence_count' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			1 => 
			array (
				'id' => 'wangyi',
				'username' => '王一',
				'licence_count' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '2015-12-13 21:32:38',
			),
			2 => 
			array (
				'id' => 'zhaier',
				'username' => '翟二',
				'licence_count' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			3 => 
			array (
				'id' => 'zhaiyi',
				'username' => '翟一',
				'licence_count' => 0,
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
