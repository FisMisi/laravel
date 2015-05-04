<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoragedVideos extends Migration 
{
	
	public function up()
	{
		Schema::create('storaged_videos', function($table)
		{
                    $table->increments('id');
                    $table->string('name');
                    $table->string('title');
                    $table->integer('model_id')->nullable();
                    $table->integer('gs_vc_id')->nullable();
                    $table->integer('user_id');
                    $table->integer('type_id');
                    $table->smallInteger('active_user')->default(1);
                    $table->smallInteger('active_admin')->default(1);
                    $table->text('inactivated_description')->nullable;
                    $table->date('publishead_and_date')->nullable();
                    $table->smallInteger('in_storage')->default(0);
                    $table->smallInteger('over_trans_code')->default(0);
                    $table->string('local_store_path',32);
                    $table->string('storage_reference',64)->nullable();
                    $table->integer('sum_rating')->default(0);
                    $table->integer('rating_number')->default(0);
                    $table->double('rating', 12, 2)->default(0);
		    $table->timestamps();
		});
	}

	
	public function down()
	{
		Schema::drop('storaged_videos');
	}

}
