<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		chema::create('orders', function($table)
		{
                    $table->increments('id');
                    $table->integer('model_id');
					$table->integer('user_id');
					$table->date('send_date');
					$table->string('message1', 256)->nullable();
					$table->string('meddage1', 256)->nullable();
					$table->smallInteger('storaged_video_type');
					$table->integer('storaged_video_id')->nullable();
					$table->smallInteger('is_said_back')->default(0);
					$table->dateTime('said_back_time')->nullable();
					$table->smallInteger('is_rejected')->default(0);
					$table->dateTime('rejected_time')->nullable();
					$table->string('rejected_reason', 256)->nullable();
					$table->smallInteger('is_inactive')->default(0);
					$table->string('inactive_reason', 128)->nullable();
					$table->integer('inactivate_user')->nullable();
					$table->dateTime('inactivate_time')->nullable();
					$table->smallInteger('status')->default(0);
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
