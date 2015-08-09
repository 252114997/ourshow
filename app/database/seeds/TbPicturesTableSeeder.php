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
				'caption' => 'abcdef百年大计，教育为本。教育是人类传承文明和知识、培养年轻一代、创造美好生活的根本途径',
			),
			1 => 
			array (
				'id' => 2,
				'name' => 'Koala',
				'path' => 'img/timeline/Koala.jpg',
				'created_at' => NULL,
				'updated_at' => NULL,
				'caption' => '级党委和政府、各级领导干部要牢固树立安全发展理念，始终把人民群众生命安全放在第一位。各地区各部门、各类企业都要坚持安全生产高标准、严要求，招商引资、上项目要严把安全生产关，加大安',
			),
			2 => 
			array (
				'id' => 3,
				'name' => 'Lighthouse',
				'path' => 'img/timeline/Lighthouse.jpg',
				'created_at' => NULL,
				'updated_at' => NULL,
				'caption' => '障性住房建设是一件利国利民的大好事，但要把这件好事办好、真正使需要帮助的住房困难群众受益，就必须加强管理，在准入、使用、退出等方面建立规范机制，实现公共资源公平善用。要坚',
			),
			3 => 
			array (
				'id' => 4,
				'name' => 'Penguins',
				'path' => 'img/timeline/Penguins.jpg',
				'created_at' => NULL,
				'updated_at' => NULL,
				'caption' => 'abcdef百年大计，教育为本。教育是人类传承文明和知识、培养年轻一代、创造美好生活的根本途径',
			),
		));
	}

}
