<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticContentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('static_contents', function($table)
		{
			$table->increments('static_content_id');
			$table->integer('parent_id')->nullable();
			$table->string('language', 2)->default('en');
			$table->string('title', 64);
			$table->string('class', 16);
			$table->text('content');
			$table->enum('type', array('front', 'email'))->default('front');
			$table->integer('create_user_id');
			$table->integer('modify_user_id')->nullable();
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
		Schema::drop('static_contents');
	}

}
