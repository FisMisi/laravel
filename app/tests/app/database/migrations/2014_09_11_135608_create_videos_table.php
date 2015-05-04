<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('videos', function($table)
		{
			$table->increments('video_id');
			$table->string('base_video_id', 32)->nullable();
			$table->integer('partner_id')->default(1);
			$table->string('video_name', 512);
			$table->string('video_seo_name', 512);
			$table->smallInteger('valid_seo')->default(1);
			$table->string('video_flash_link', 512);
			$table->string('default_thumb', 512);
			$table->time('length');
			$table->smallInteger('active')->default(1);
			$table->smallInteger('active2')->default(1);
			$table->dateTime('inactivation_time')->nullable();
			$table->integer('inactivation_user_id')->nullable();
			$table->text('inactivation_reason')->nullable();
			$table->integer('sum_rating')->default(0);
			$table->integer('rating_number')->default(0);
			$table->integer('sum_rating_l')->default(0);
			$table->integer('rating_number_l')->default(0);
			$table->integer('advertising_number')->nullable();
			$table->smallInteger('insitemap')->default(0);
			$table->integer('sitemapnum')->default(0);
			
			$table->double('rating', 12, 2)->default(0);
			$table->double('rating_l', 12, 2)->default(0);
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
		Schema::drop('videos');
	}

}
