<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GsMlsGsVcTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('gs_mls_gs_vc', function($table)
		{
                    $table->increments('id');
					
					$table->integer('gs_model_level_id');
					$table->integer('gs_video_category_id');
					$table->smallInteger('is_exclusive')->default(1);
					$table->integer('min');
					$table->integer('max');
					$table->integer('referenced_price');
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
		//
	}

}
