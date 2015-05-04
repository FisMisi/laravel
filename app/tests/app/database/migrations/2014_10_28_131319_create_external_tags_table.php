<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('external_tags', function($table)
		{
			$table->increments('external_tag_id');
			$table->string('external_tag_name', 64);
			$table->integer('partner_id')->default(1);
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
		Schema::drop('external_tags');
	}

}
