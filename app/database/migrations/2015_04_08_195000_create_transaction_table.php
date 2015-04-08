<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	 Schema::create('transactions_paypal', function($table){
                $table->increments('id');
                $table->integer('user_id');
                $table->string('payment_id');
                $table->smallInteger('complate')->default(0);
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
                    'transactions_paypal'
                    );
	}

}
