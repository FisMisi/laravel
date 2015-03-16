<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::table('category_types', function($table){
                $table->boolean('active')->default(1);
                $table->boolean('multi')->default(0);
            });
            
            Schema::table('categories', function($table){
                $table->boolean('active')->default(1);
                $table->integer('pos');
                $table->integer('type_id');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::table('category_types', function($table)
            {
                $table->dropColumn('active');
            });
            Schema::table('categories', function($table)
            {
                $table->dropColumn('active');
                $table->dropColumn('pos');
            });
	}

}
