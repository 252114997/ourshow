<?php

class AccountSeeder extends Seeder {

	public function run() {
		DB::table('tb_account')->truncate();

		$role_ids = DB::table('tb_role')->lists('id', 'name');
		$now = Date('Y-m-d H:m:s');

		$data = array(
			array(
				'username' => 'admin',
				'email' => 'ws@wholeton.com',
				'password' => Hash::make('admin'),
				'description' => '内置账号，不可编辑',
				'created_at' => $now,
				'updated_at' => $now,
				'role_id' => $role_ids['超级管理员']
			),
		);

		DB::table('tb_account')->insert($data);
	}
}
