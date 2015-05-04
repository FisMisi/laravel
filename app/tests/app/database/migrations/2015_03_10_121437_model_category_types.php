<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModelCategoryTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('model_category_types', function($table)
            {
                $table->increments('id');
                $table->integer('pos');
                $table->string('name');
                $table->string('title');
                $table->integer('active')->default(1);
                $table->enum('multi', array(0, 0))->default(0);
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('model_category_types');
	}

}
