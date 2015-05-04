<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('modules', function($table) {
			$table->increments('modul_id');
			$table->string('modul_name', 32);
			$table->string('modul_title', 32);
			$table->string('helper', 64);
			$table->string('admin_route', 64);
			$table->smallInteger('has_public')->default(1);
			$table->timestamps();
		});
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
