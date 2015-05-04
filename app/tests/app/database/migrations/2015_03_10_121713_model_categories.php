<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModelCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('model_categories', function($table)
            {
                $table->increments('id');
                $table->integer('type_id');
                $table->integer('pos');
                $table->string('name');
                $table->string('title');
                $table->integer('active')->default(1);
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('model_categories');
	}

}
