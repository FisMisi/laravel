<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoryTypes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('category_types', function($table){
                $table->increments('id');
                $table->integer('category_id');
                $table->string('name');
                $table->string('title');
                $table->integer('pos')->nullable();
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop(
                    'categoy_types'
            );
	}

}
