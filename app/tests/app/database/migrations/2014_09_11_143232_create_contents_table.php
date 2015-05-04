<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contents', function($table)
		{
			$table->increments('content_id');
			$table->integer('routing_id');
			$table->string('container_name', 64);
			$table->integer('pos');
			$table->string('modul', 64)->default('Custom');
			$table->string('helper_class', 64);
			$table->string('helper_function', 64)->default('getViewDatas');
			$table->string('helper_data_json', 256)->nullable();
			$table->string('routing_data_json', 256)->nullable();
			$table->smallInteger('active')->default(1);
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
		Schema::drop('contents');
	}

}
