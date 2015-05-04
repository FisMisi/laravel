<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsNTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ratings_n', function($table)
		{
			$table->increments('rating_id');
			$table->integer('video_id');
			$table->integer('user_id')->nullable();
			$table->integer('rating')->nullable();
			$table->string('session_id', 40);
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
