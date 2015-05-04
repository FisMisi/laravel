<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banner_types', function($table)
		{
			$table->increments('banner_type_id');
			$table->string('name', 64);
			$table->string('title', 64);
			$table->string('link', 256);
			$table->string('picture_src', 128)->nullable();
			$table->string('flash_src', 128)->nullable();
			$table->string('iframe_src', 128)->nullable();
			$table->string('flashvars', 256)->nullable();
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
		Schema::drop('banner_types');
	}

}
