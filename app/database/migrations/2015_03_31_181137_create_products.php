<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProducts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	 Schema::create('products', function($table){
                $table->increments('id');
                $table->string('name');
                $table->smallInteger('active_user')->default(1);
                $table->smallInteger('active_admin')->default(0);
                $table->integer('category_types_id');
                $table->integer('categories_id');
                $table->integer('user_id');
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
            Schema::drop(
                    'categories'
                    );
	}
}
