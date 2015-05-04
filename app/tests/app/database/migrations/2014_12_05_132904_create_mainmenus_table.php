<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainmenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mainmenus', function($table)
		{
			$table->increments('mainmenu_id');
			$table->string('name', 64);
			$table->string('title', 64);
			$table->string('target', 16)->nullable();
			$table->string('href', 64)->default('javascript:void(0);');
			$table->string('onclick', 64)->nullable();
			$table->integer('pos')->default(999);
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
		//
	}

}
