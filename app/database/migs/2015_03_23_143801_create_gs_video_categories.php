<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGsVideoCategories extends Migration {

	
	public function up()
	{
	   Schema::create('gs_video_categories', function($table)
            {
                $table->increments('id');
                $table->string('name');
                $table->string('title');
                $table->smallInteger('active')->default(1);
            });
	}

	public function down()
	{
            Schema::drop('gs_video_categories');
	}

}
