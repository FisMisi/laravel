<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function($table)
		{
			$table->increments('comment_id');
			$table->integer('user_id');
			$table->integer('video_id');
			$table->text('comment');
			$table->smallInteger('active')->default(1);
			$table->dateTime('inactive_time')->nullable();
			$table->integer('inactive_user_id')->nullable();
			$table->string('inactive_reason', 128)->nullable();
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
		Schema::drop('comments');
	}

}
