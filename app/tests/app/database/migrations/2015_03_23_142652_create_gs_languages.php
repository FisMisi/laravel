<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGsLanguages extends Migration {

	public function up()
	{
	   Schema::create('gs_languages', function($table)
            {
                $table->increments('id');
                $table->string('sort',3);
                $table->string('name');
                $table->smallInteger('active')->default(1);
            });
	}

	public function down()
	{
            Schema::drop('gs_languages');
	}
}
