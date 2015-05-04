<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposerTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposer_types', function($table)
		{
			$table->increments('proposer_type_id');
			$table->string('name', 64);
			$table->string('title', 64);
			$table->string('where_json_data', 512)->nullable();
			$table->string('where_sql', 128)->default('true');
			$table->string('order_json_data', 512)->nullable();
			$table->string('order_sql', 128)->default('video_id DESC');
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
		Schema::drop('proposer_types');
	}

}
