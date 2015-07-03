<?php

class TbPostsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('tb_posts')->truncate();
        
		\DB::table('tb_posts')->insert(array (
			array (
				'ablum_id' => 1,
				'user_id' => 2,
				'text' => 'orz...',
				'created_at' => '2015-06-14 14:21:46',
				'updated_at' => '2015-06-14 14:21:46',
			),
			array (
				'ablum_id' => 1,
				'user_id' => 2,
				'text' => 'orz...',
				'created_at' => '2015-06-14 14:21:46',
				'updated_at' => '2015-06-14 14:21:46',
			),
		));
	}

}
