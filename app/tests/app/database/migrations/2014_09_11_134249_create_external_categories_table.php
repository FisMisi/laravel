<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('external_categories', function($table)
		{
			$table->increments('external_category_id');
			$table->string('category_origin_name', 64);
			$table->string('actualname', 64);
			$table->integer('partner_id')->default(1);
			$table->smallInteger('heed')->default(1);
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
		Schema::drop('external_categories');
	}

}
