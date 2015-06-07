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
				'id' => 1,
				'ablum_id' => 1,
				'user_id' => 1,
				'text' => '帅',
				'created_at' => '2015-06-04 18:44:08',
				'updated_at' => '2015-06-04 18:44:12',
			),
			1 => 
			array (
				'id' => 2,
				'ablum_id' => 1,
				'user_id' => 2,
				'text' => '好',
				'created_at' => '2015-06-03 18:44:16',
				'updated_at' => '2015-06-16 18:44:19',
			),
			2 => 
			array (
				'id' => 3,
				'ablum_id' => 1,
				'user_id' => 3,
				'text' => '太棒了',
				'created_at' => '2015-06-01 18:44:23',
				'updated_at' => '2015-06-01 18:44:23',
			),
			3 => 
			array (
				'id' => 4,
				'ablum_id' => 1,
				'user_id' => 3,
				'text' => '哈哈',
				'created_at' => '2015-06-01 18:44:23',
				'updated_at' => '2015-06-01 18:44:23',
			),
			4 => 
			array (
				'id' => 5,
				'ablum_id' => 2,
				'user_id' => 1,
				'text' => '嘿嘿',
				'created_at' => '2015-06-01 18:44:23',
				'updated_at' => '2015-06-01 18:44:23',
			),
			5 => 
			array (
				'id' => 21,
				'ablum_id' => 1,
				'user_id' => 1,
				'text' => 'bdj',
				'created_at' => '2015-06-06 16:43:37',
				'updated_at' => '2015-06-06 16:43:37',
			),
			6 => 
			array (
				'id' => 22,
				'ablum_id' => 1,
				'user_id' => 1,
				'text' => 'bdj',
				'created_at' => '2015-06-06 16:43:51',
				'updated_at' => '2015-06-06 16:43:51',
			),
			7 => 
			array (
				'id' => 23,
				'ablum_id' => 1,
				'user_id' => 1,
				'text' => 'hcjmd',
				'created_at' => '2015-06-06 16:45:27',
				'updated_at' => '2015-06-06 16:45:27',
			),
			8 => 
			array (
				'id' => 24,
				'ablum_id' => 1,
				'user_id' => 1,
				'text' => 'wiw this wendfull!  are you super man',
				'created_at' => '2015-06-06 16:45:54',
				'updated_at' => '2015-06-06 16:45:54',
			),
			9 => 
			array (
				'id' => 25,
				'ablum_id' => 1,
				'user_id' => 1,
				'text' => 'wiw this wendfull!  are you super man',
				'created_at' => '2015-06-06 16:46:01',
				'updated_at' => '2015-06-06 16:46:01',
			),
			10 => 
			array (
				'id' => 26,
				'ablum_id' => 2,
				'user_id' => 1,
				'text' => 'fdhfdg',
				'created_at' => '2015-06-06 16:46:26',
				'updated_at' => '2015-06-06 16:46:26',
			),
			11 => 
			array (
				'id' => 27,
				'ablum_id' => 2,
				'user_id' => 1,
				'text' => 'fdhfdgjzkk',
				'created_at' => '2015-06-06 16:46:30',
				'updated_at' => '2015-06-06 16:46:30',
			),
			12 => 
			array (
				'id' => 28,
				'ablum_id' => 3,
				'user_id' => 1,
				'text' => 'xzcv',
				'created_at' => '2015-06-06 16:50:42',
				'updated_at' => '2015-06-06 16:50:42',
			),
			13 => 
			array (
				'id' => 29,
				'ablum_id' => 3,
				'user_id' => 1,
				'text' => 'xzcvsfff',
				'created_at' => '2015-06-06 16:50:48',
				'updated_at' => '2015-06-06 16:50:48',
			),
			14 => 
			array (
				'id' => 30,
				'ablum_id' => 3,
				'user_id' => 1,
				'text' => 'xzcvsfffzxcv',
				'created_at' => '2015-06-06 16:50:50',
				'updated_at' => '2015-06-06 16:50:50',
			),
			15 => 
			array (
				'id' => 31,
				'ablum_id' => 1,
				'user_id' => 1,
				'text' => 'xzcvzxcvsadafasdfwer',
				'created_at' => '2015-06-06 16:51:03',
				'updated_at' => '2015-06-06 16:51:03',
			),
			16 => 
			array (
				'id' => 32,
				'ablum_id' => 4,
				'user_id' => 1,
				'text' => 'hdnnjc',
				'created_at' => '2015-06-06 21:59:12',
				'updated_at' => '2015-06-06 21:59:12',
			),
			17 => 
			array (
				'id' => 33,
				'ablum_id' => 3,
				'user_id' => 1,
				'text' => '替他是的了我自己有不啊哈哈我自己是',
				'created_at' => '2015-06-06 21:59:30',
				'updated_at' => '2015-06-06 21:59:30',
			),
			18 => 
			array (
				'id' => 34,
				'ablum_id' => 1,
				'user_id' => 2,
				'text' => 'vxcv',
				'created_at' => '2015-06-07 11:01:34',
				'updated_at' => '2015-06-07 11:01:34',
			),
			19 => 
			array (
				'id' => 35,
				'ablum_id' => 2,
				'user_id' => 3,
				'text' => 'zhayi',
				'created_at' => '2015-06-07 11:02:50',
				'updated_at' => '2015-06-07 11:02:50',
			),
			20 => 
			array (
				'id' => 36,
				'ablum_id' => 3,
				'user_id' => 3,
				'text' => 'cvb',
				'created_at' => '2015-06-07 11:02:55',
				'updated_at' => '2015-06-07 11:02:55',
			),
			21 => 
			array (
				'id' => 37,
				'ablum_id' => 4,
				'user_id' => 3,
				'text' => 'bvcb',
				'created_at' => '2015-06-07 11:02:59',
				'updated_at' => '2015-06-07 11:02:59',
			),
			22 => 
			array (
				'id' => 38,
				'ablum_id' => 4,
				'user_id' => 3,
				'text' => 'bvcbbcvb',
				'created_at' => '2015-06-07 11:03:03',
				'updated_at' => '2015-06-07 11:03:03',
			),
			23 => 
			array (
				'id' => 39,
				'ablum_id' => 4,
				'user_id' => 3,
				'text' => 'bvcbbcvbff',
				'created_at' => '2015-06-07 11:03:05',
				'updated_at' => '2015-06-07 11:03:05',
			),
			24 => 
			array (
				'id' => 40,
				'ablum_id' => 4,
				'user_id' => 3,
				'text' => 'bvcbbcvbffasdfwe',
				'created_at' => '2015-06-07 11:03:08',
				'updated_at' => '2015-06-07 11:03:08',
			),
		));
	}

}
