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

/*Admin section*/
Route::group(array('prefix'=>'admin'),function()
{
    Route::get('users/index',array('as'=>'admin.users.index', 'uses' =>'AdminUserController@index'));
    Route::post('users/index',array('as'=>'admin.users.postindex', 'uses' =>'AdminUserController@index'));
    Route::resource('users','AdminUserController',array('except' => array('show')));
    
    Route::resource('categories','AdminCategoryController', array('except' => array('show')));
    
    Route::get('menuitems/index',array('as'=>'admin.menuitems.index', 'uses' =>'AdminMenuitemController@index'));
    Route::post('menuitems/index',array('as'=>'admin.menuitems.postindex', 'uses' =>'AdminMenuitemController@index'));
    Route::resource('menuitems','AdminMenuitemController', array('except' => array('show')));    
});


/*end Admin section*/


Route::get('/', 'UserController@getLogin');


Route::get('login',array('as'=>'login', 'uses' =>'UserController@getLogin'));
Route::post('login',array('as'=>'login.post', 'uses' =>'UserController@postLogin'));
Route::get('logout',array('as'=>'logout', 'uses' =>'UserController@getLogout'));

Route::any('users/create', array('as' => 'users.create', 'uses' => 'UserController@create'));
Route::any('users/register', array('as' => 'users.register', 'uses' => 'UserController@store'));
Route::any('users/update/{id}', array('as' => 'users.update', 'uses' => 'UserController@update'));
Route::any('users/editUser', array('as' => 'users.editUser', 'uses' => 'UserController@editUser'));

Route::resource('categories', 'CategoryController');
Route::post('categories/destroy/{id}', array('as' => 'delCateg', 'uses' => 'CategoryController@delCateg'));


//Route::any('menuitems/{id}', array('as' => 'post', 'uses' => 'PostController@getIndex'))->where('id', '[1-9][0-9]*');
Route::any('menuitems', array('as' => 'menuitems.index', 'uses' => 'MenuitemsController@index'));
Route::any('menuitems/create', array('as' => 'menuitems.create', 'uses' => 'MenuitemsController@create'));
Route::any('menuitems/createItem', array('as' => 'menuitems.createItem', 'uses' => 'MenuitemsController@store'));
Route::post('menuitems/destroy/{id}', array('as' => 'delItem', 'uses' => 'MenuitemsController@delProd'));
Route::any('menuitems/show/{id}', array('as'=>'menuitems.show', 'uses'=>'MenuitemsController@getShow'));
Route::any('menuitems/search', array('as'=>'menuitems.search', 'uses'=>'MenuitemsController@getSearch'));