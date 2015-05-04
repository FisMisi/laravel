<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('routings', function($table)
		{
			$table->increments('id');
			$table->string('routing_path', 128);
			$table->string('layout_name', 64);
			$table->string('routing_name', 64)->default('livechannel');
			$table->smallInteger('active')->default(1);
			$table->smallInteger('needover18')->default(1);
			$table->smallInteger('need_auth')->default(0);
			$table->smallInteger('need_admin_auth')->default(0);
			$table->smallInteger('system_route')->default(0);
			$table->smallInteger('adminheader')->default(0);
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
		Schema::drop('routings');
	}

}
