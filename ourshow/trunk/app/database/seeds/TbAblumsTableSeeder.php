<?php

class TbAblumsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tb_ablums')->truncate();
        
		\DB::table('tb_ablums')->insert(array (
			0 => 
			array (
				'id' => 1,
				'title' => '结婚',
				'caption' => NULL,
				'picture_id' => 1,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			1 => 
			array (
				'id' => 2,
				'title' => '有欢笑',
				'caption' => NULL,
				'picture_id' => 2,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			2 => 
			array (
				'id' => 3,
				'title' => '幸福一直在',
				'caption' => NULL,
				'picture_id' => 3,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			3 => 
			array (
				'id' => 4,
				'title' => '相聚',
				'caption' => NULL,
				'picture_id' => 4,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
		));
	}

}
