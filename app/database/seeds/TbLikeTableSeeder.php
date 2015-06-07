<?php

class TbLikeTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tb_like')->truncate();
        
		\DB::table('tb_like')->insert(array (
			0 => 
			array (
				'id' => 22,
				'ablum_id' => 3,
				'user_id' => 1,
				'created_at' => '2015-06-06 21:57:09',
				'updated_at' => '2015-06-06 21:57:09',
			),
		));
	}

}
