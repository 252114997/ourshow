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
				'id' => '1',
				'title' => '01相识',
				'caption' => NULL,
				'tips' => '01相识',
				'picture_id' => '9d377b10ce778c4938b3c7e2c63a229a',
				'created_at' => '2016-02-19 22:22:37',
				'updated_at' => '2016-02-19 22:22:37',
			),
			1 => 
			array (
				'id' => '2',
				'title' => '02相恋',
				'caption' => NULL,
				'tips' => '02相恋',
				'picture_id' => 'ac037a6becf9784759f2f9c4edaca16b',
				'created_at' => '2016-02-19 22:22:37',
				'updated_at' => '2016-02-19 22:22:37',
			),
			2 => 
			array (
				'id' => '3',
				'title' => '03相伴',
				'caption' => NULL,
				'tips' => '03相伴',
				'picture_id' => '2381933debf0f1b026ebf6f8b74014fc',
				'created_at' => '2016-02-19 22:22:37',
				'updated_at' => '2016-02-19 22:22:37',
			),
			3 => 
			array (
				'id' => '4',
				'title' => '04相爱',
				'caption' => NULL,
				'tips' => '04相爱',
				'picture_id' => '6b226d546d28f4672203dca985936483',
				'created_at' => '2016-02-19 22:22:37',
				'updated_at' => '2016-02-19 22:22:37',
			),
			4 => 
			array (
				'id' => '5',
				'title' => 'background',
				'caption' => NULL,
				'tips' => 'background',
				'picture_id' => '8f1e23597b31d274efac6efef142b347',
				'created_at' => '2016-02-19 22:22:37',
				'updated_at' => '2016-02-19 22:22:37',
			),
		));
	}

}
