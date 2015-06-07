<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('TbAblumPictureTableSeeder');
		$this->call('TbAblumsTableSeeder');
		$this->call('TbLikeTableSeeder');
		$this->call('TbPicturesTableSeeder');
		$this->call('TbPostsTableSeeder');
		$this->call('TbUserTokenTableSeeder');
		$this->call('TbUsersTableSeeder');
	}
}