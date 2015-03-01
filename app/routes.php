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

Route::get('/', 'CategoryController@index');


Route::get('login',array('as'=>'login', 'uses' =>'UserController@getLogin'));
Route::post('login',array('as'=>'login.post', 'uses' =>'UserController@postLogin'));
Route::get('logout',array('as'=>'logout', 'uses' =>'UserController@getLogout'));

Route::any('users/create', array('as' => 'users.create', 'uses' => 'UserController@create'));
Route::any('users/register', array('as' => 'users.register', 'uses' => 'UserController@store'));

Route::resource('categories', 'CategoryController');
Route::post('categories/destroy/{id}', array('as' => 'delCateg', 'uses' => 'CategoryController@delCateg'));


//Route::any('menuitems/{id}', array('as' => 'post', 'uses' => 'PostController@getIndex'))->where('id', '[1-9][0-9]*');
Route::any('menuitems', array('as' => 'menuitems.index', 'uses' => 'MenuitemsController@index'));
Route::any('menuitems/create', array('as' => 'menuitems.create', 'uses' => 'MenuitemsController@create'));
Route::any('menuitems/createItem', array('as' => 'menuitems.createItem', 'uses' => 'MenuitemsController@store'));
Route::post('menuitems/destroy/{id}', array('as' => 'delItem', 'uses' => 'MenuitemsController@delProd'));
Route::any('menuitems/show/{id}', array('as'=>'menuitems.show', 'uses'=>'MenuitemsController@getShow'));
Route::any('menuitems/search', array('as'=>'menuitems.search', 'uses'=>'MenuitemsController@getSearch'));