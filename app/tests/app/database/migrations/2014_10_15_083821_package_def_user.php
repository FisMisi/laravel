<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageDefUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('users')->insert(
			array(
				'user_id' => 1,
				'email' => 'paronai.tamas@ikron.hu',
				'password' => '$2y$10$E849VPeJV0P0c5SFHfpyfupmCTpiODokeO7L8Un.QDEO4/TNa19iW',
				'admin' => 1,
				'active' => 1,
				'confirmed' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
				'confirm_valid_time' => date('Y-m-d h:i:s')
			)
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
