<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoragedSeeVideos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('storaged_see_videos', function($table)
		{
                    $table->increments('id');
                    $table->integer('storaged_video_id');
                    $table->integer('see_count');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('storaged_see_videos');
	}

}
