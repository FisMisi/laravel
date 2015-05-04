<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageDataLanguages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('languages')->insert(
			array(
				'short' => 'hu',
				'long' => 'Magyar',
				'active' => 0,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('languages')->insert(
			array(
				'short' => 'en',
				'long' => 'English',
				'active' => 1,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
			)
		);
		
		DB::table('languages')->insert(
			array(
				'short' => 'de',
				'long' => 'Deutsch',
				'active' => 0,
				'created_at' => date('Y-m-d h:i:s'),
				'updated_at' => date('Y-m-d h:i:s'),
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
