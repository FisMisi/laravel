<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelLanguage extends Migration {

	public function up()
	{
	   Schema::create('model_language', function($table)
            {
                $table->increments('id');
                $table->integer('gs_language_id');
                $table->integer('model_id');
            });
	}

	public function down()
	{
            Schema::drop('model_language');
	}
}
