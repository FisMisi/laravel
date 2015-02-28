<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('categories.index');
});


Route::resource('categories', 'CategoryController');
Route::post('categories/destroy/{id}', array('as' => 'delCateg', 'uses' => 'CategoryController@delCateg'));


//Route::any('menuitems/{id}', array('as' => 'post', 'uses' => 'PostController@getIndex'))->where('id', '[1-9][0-9]*');
Route::any('menuitems', array('as' => 'menuitems.index', 'uses' => 'MenuitemsController@index'));
Route::any('menuitems/create', array('as' => 'menuitems.create', 'uses' => 'MenuitemsController@create'));
Route::any('menuitems/createItem', array('as' => 'menuitems.createItem', 'uses' => 'MenuitemsController@store'));
Route::post('menuitems/destroy/{id}', array('as' => 'delItem', 'uses' => 'MenuitemsController@delProd'));