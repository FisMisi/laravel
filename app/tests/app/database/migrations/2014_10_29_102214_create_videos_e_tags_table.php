<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosETagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('videos_e_tags', function($table)
		{
			$table->increments('videos_e_tag_id');
			$table->integer('video_id');
			$table->integer('external_tag_id');
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
		Schema::drop('videos_e_tags');
	}

}
