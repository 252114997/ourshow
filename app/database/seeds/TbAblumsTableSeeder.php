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
				'title' => '出世',
				'caption' => '一只爬行帅哥 ',
				'tips' => '@ 1995.10.08',
				'picture_id' => '9fff297dc9559d7884c8388eb712d6c9',
				'created_at' => '1995-10-08 13:23:22',
				'updated_at' => '1995-10-08 13:23:22',
			),
			1 => 
			array (
				'id' => 2,
				'title' => '大学',
				'caption' => '辗转来到大学，我们在茫茫人海中相识',
				'tips' => '@ 2009.09.01',
				'picture_id' => '9fcf40bb6ae65610975e5626a2c03af7',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			2 => 
			array (
				'id' => 3,
				'title' => '相爱',
				'caption' => '开始实习工作的前一天，我各位同学的见证下向她表白。感谢大家的祝福，特别感谢hl给我的勇气与支持。',
				'tips' => '@ 2011.10.08',
				'picture_id' => '09366bc7e9f9ee9bd250ea0490637f9b',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
			3 => 
			array (
				'id' => 4,
				'title' => '工作',
				'caption' => '工作不忙的时候，抽空出去于',
				'tips' => '@ 2012.11.08',
				'picture_id' => '6121c0e5106ee0924e1e00ccd3724bd0',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
			),
		));
	}

}
