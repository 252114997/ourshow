<?php

class TbAblumPictureTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tb_ablum_picture')->truncate();
        
		\DB::table('tb_ablum_picture')->insert(array (
			array (
				'ablum_id' => 1,
				'picture_id' => 1,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 1,
				'picture_id' => 2,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 1,
				'picture_id' => 3,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 1,
				'picture_id' => 4,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 2,
				'picture_id' => 1,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 2,
				'picture_id' => 2,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 2,
				'picture_id' => 3,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 3,
				'picture_id' => 1,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 3,
				'picture_id' => 2,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			array (
				'ablum_id' => 4,
				'picture_id' => 1,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
		));
	}

}
