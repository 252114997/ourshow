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
				'id' => 23,
				'ablum_id' => 1,
				'user_id' => 'wangyi',
				'created_at' => '2015-12-13 21:24:08',
				'updated_at' => '2015-12-13 21:24:08',
			),
		));
	}

}
