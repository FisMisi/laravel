<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StoragedSeeVideosDayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('storaged_see_videos_day', function($table)
		{
                    $table->increments('id');
					$table->integer('storaged_video_id');
					$table->integer('see_count');
					$table->date('day');
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
