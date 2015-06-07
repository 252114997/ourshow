<?php

class TbPicturesTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tb_pictures')->truncate();
        
		\DB::table('tb_pictures')->insert(array (
			0 => 
			array (
				'id' => 1,
				'name' => 'Hydrangeas',
				'path' => 'img/timeline/Hydrangeas.jpg',
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Koala',
				'path' => 'img/timeline/Koala.jpg',
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Lighthouse',
				'path' => 'img/timeline/Lighthouse.jpg',
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'Penguins',
				'path' => 'img/timeline/Penguins.jpg',
				'created_at' => NULL,
				'updated_at' => NULL,
			),
		));
	}

}
