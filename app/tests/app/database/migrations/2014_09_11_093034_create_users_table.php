<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('user_id');
			$table->smallInteger('admin')->default(0);
			$table->smallInteger('active')->default(1);
			$table->string('password', 60);
			$table->dateTime('inactive_time')->nullable();
			$table->integer('inactive_user')->nullable();
			$table->string('inactiv_reason', 64)->nullable();
			$table->string('nick', 32)->nullable();
			$table->smallInteger("sex")->nullable();
			$table->string('email', 255)->unique();
			$table->string('first_name', 32)->nullable();
			$table->string('last_name', 32)->nullable();
			$table->smallInteger('confirmed')->default(0);
			$table->string('confirm_link',64)->nullable();
			$table->dateTime('confirm_valid_time')->nullable();
			$table->string('new_password_link', 64)->nullable();
			$table->dateTime('new_password_valid_time')->nullable();
			$table->string('remember_token', 100)->nullable();
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
		Schema::drop('users');
	}

}
