<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
             Schema::create('customers', function($table){
                $table->increments('id');
                $table->string('first_name',30);
                $table->string('last_name',30);
                $table->string('email')->unique();
                $table->decimal('postal_code',4)->unsigned();
                $table->string('city',30);
                $table->string('address',50);
                $table->string('phone',30);
                $table->string('img_path');
                $table->softDeletes();
            });
            
            Schema::create('users', function($table){
                $table->increments('id');
                $table->string('first_name',30);
                $table->string('last_name',30);
                $table->string('username')->unique();
                $table->string('password',100);
                $table->rememberToken();
                $table->softDeletes();
            });
            
            Schema::create('menuitems', function($table){
                $table->increments('id');
                $table->string('name',30);
                $table->integer('price')->unsigned();
                $table->enum('type', array('étel', 'ital'))->default('étel');
                $table->softDeletes();
            });
            
            Schema::create('pizzadeliveries', function($table){
                $table->increments('id');
                $table->string('first_name',30);
                $table->string('last_name',30);
                $table->string('nickname',30)->unique();
                $table->string('phone',30);
                $table->softDeletes();
            });
            
            Schema::create('orders', function($table){
                $table->increments('id');
                $table->integer('customer_id')->unsigned();
                $table->foreign('customer_id')->references('id')->on('customers');
                $table->integer('pizzadelivery_id')->unsigned()->nullable();
                $table->foreign('pizzadelivery_id')->references('id')->on('pizzadeliveries');
                $table->dateTime('term')->nullable();
                $table->enum('statusz', array('új', 'előkészítés', 'szállítás', 'átvett', 'visszaküldött'))->default('új');
                $table->softDeletes();
                $table->timestamps();  
            });
            
            Schema::create('menuitem_order', function($table){
                $table->increments('id');
                $table->integer('order_id')->unsigned();
                $table->foreign('order_id')->references('id')->on('orders');
                $table->integer('menuitem_id')->unsigned();
                $table->foreign('menuitem_id')->references('id')->on('menuitems');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
            Schema::drop('menuitem_order');
            Schema::drop('orders');
            Schema::drop('customers');
            Schema::drop('users');
            Schema::drop('menuitems');
            Schema::drop('pizzadeliveries');
            
	}

}
