<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelGsVc extends Migration {

	public function up()
	{
	   Schema::create('model_gs_vc', function($table)
            {
                $table->increments('id');
                $table->integer('gs_vc_id');
                $table->integer('model_id');
                $table->integer('gs_vc_price');
                $table->integer('ex_vc_price');
                $table->smallInteger('active')->default(1);
            });
	}

	public function down()
	{
            Schema::drop('model_gs_vc');
	}


}
