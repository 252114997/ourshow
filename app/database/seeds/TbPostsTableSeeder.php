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
			0 => 
			array (
				'id' => 3,
				'ablum_id' => 2,
				'user_id' => 'wangyi',
				'text' => 'haha',
				'created_at' => '2015-12-13 21:24:05',
				'updated_at' => '2015-12-13 21:24:05',
			),
			1 => 
			array (
				'id' => 4,
				'ablum_id' => 1,
				'user_id' => 'wangyi',
				'text' => '好吧。',
				'created_at' => '2015-12-13 21:24:14',
				'updated_at' => '2015-12-13 21:24:14',
			),
			2 => 
			array (
				'id' => 5,
				'ablum_id' => 1,
				'user_id' => 'wangyi',
				'text' => '厉害 ',
				'created_at' => '2015-12-13 21:24:17',
				'updated_at' => '2015-12-13 21:24:17',
			),
			3 => 
			array (
				'id' => 6,
				'ablum_id' => 1,
				'user_id' => 'wangyi',
				'text' => '妈okj',
				'created_at' => '2015-12-13 21:29:22',
				'updated_at' => '2015-12-13 21:29:22',
			),
			4 => 
			array (
				'id' => 7,
				'ablum_id' => 1,
				'user_id' => 'wangyi',
				'text' => 'xzcv',
				'created_at' => '2015-12-13 21:30:04',
				'updated_at' => '2015-12-13 21:30:04',
			),
		));
	}

}
