<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('internal_tags', function($table)
		{
			$table->increments('internal_tag_id');
			$table->string('internal_tag_name', 64);
			$table->string('internal_tag_seo_name', 64);
			$table->integer('see_count')->default(0);
			$table->integer('category_group')->nullable();
			$table->integer('pos')->default(999);
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
		Schema::drop('internal_tags');
	}

}
