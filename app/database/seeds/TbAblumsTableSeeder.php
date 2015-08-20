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
				'tips' => '@ 2011.11.08',
				'caption' => '在本月的一次行业大会上，我们遇到了一些中国企业家、同事和朋友——当然，立即获邀玩一玩他们的智能手机。中国人通过智能手机过上了丰富多彩的生活，这让我们艳羡不已。中国人甚至开发出一种商业模式，可以合法地在手机上播放新一季《权力的游戏》。',
				'picture_id' => 1,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			1 => 
			array (
				'id' => 2,
				'title' => '有欢笑',
				'tips' => '@ 2011.11.08',
				'caption' => '在本月的一次行业大会上，我们遇到了一些中国企业家、同事和朋友——当然，立即获邀玩一玩他们的智能手机。中国人通过智能手机过上了丰富多彩的生活，这让我们艳羡不已。中国人甚至开发出一种商业模式，可以合法地在手机上播放新一季《权力的游戏》。',
				'picture_id' => 2,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			2 => 
			array (
				'id' => 3,
				'title' => '幸福一直在',
				'tips' => '@ 2011.11.08',
				'caption' => '在本月的一次行业大会上，我们遇到了一些中国企业家、同事和朋友——当然，立即获邀玩一玩他们的智能手机。中国人通过智能手机过上了丰富多彩的生活，这让我们艳羡不已。中国人甚至开发出一种商业模式，可以合法地在手机上播放新一季《权力的游戏》。',
				'picture_id' => 3,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
			3 => 
			array (
				'id' => 4,
				'title' => '相聚',
				'tips' => '@ 2011.11.08',
				'caption' => '在本月的一次行业大会上，我们遇到了一些中国企业家、同事和朋友——当然，立即获邀玩一玩他们的智能手机。中国人通过智能手机过上了丰富多彩的生活，这让我们艳羡不已。中国人甚至开发出一种商业模式，可以合法地在手机上播放新一季《权力的游戏》。',
				'picture_id' => 4,
				'created_at' => NULL,
				'updated_at' => NULL,
			),
		));
	}

}
