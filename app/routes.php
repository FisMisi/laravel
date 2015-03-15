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
Route::group(array('before' => 'auth'),function()
{
    Route::get('/admin/users/index',array('as'=>'admin.users.index', 'uses' =>'AdminUserController@index'));
    Route::post('/admin/users/index',array('as'=>'admin.users.postindex', 'uses' =>'AdminUserController@index'));
    Route::resource('/admin/users','AdminUserController',array('except' => array('show')));

    //főkategóriák listázása (types)
    Route::get('/admin/categories', array('as' => 'admin.categories.index', 'uses' => 'AdminCategoryController@index'));
    //főkategóriákhoz tartozó alkategóriák listázása
    Route::get('/admin/categories/{id}', array('as' => '/admin/categories/{id}', 'uses' => 'AdminCategoryController@lists'));
    
    //főkategória létrehozása form
    Route::get('/admin/categories/type/create',array('as'=>'admin.categories.type.create', 'uses' =>'AdminCategoryController@createType'));
   
    //feldolgozó főkategória létrehozása
    Route::post('/admin/categories/type/save',array('as'=>'admin.categories.type.save', 'uses' =>'AdminCategoryController@saveCategoryType'));
    //főkategória szerkesztés form
    Route::any('/admin/categories/type/edit/{id}',array('as'=>'admin.categories.type.edit', 'uses' =>'AdminCategoryController@editCategoryType'));
    //főkategória mentés
    Route::any('/admin/categories/type/update/{id}',array('as'=>'admin.categories.categoryType.update', 'uses' =>'AdminCategoryController@updateCategoryType'));
    //főkategória státusz mentés                                                           
    Route::any('/admin/categories/type/edit_statusz/{id}', array('as' => 'admin.categories.type.editStatusz', 'uses' => 'AdminCategoryController@editStatusz'));
    //kategória státusz mentés                                                           
    Route::any('/admin/categories/cat/edit_statusz/{id}', array('as' => 'admin.categories.cat.editCatStatusz', 'uses' => 'AdminCategoryController@editCatStatusz'));
    //főkategória létrehozása form
    Route::any('/admin/categories/cat/create/{id}',array('as'=>'admin.categories.cat.create', 'uses' =>'AdminCategoryController@createCategory'));
     //feldolgozó kategória létrehozása
    Route::any('/admin/categories/cat/save',array('as'=>'admin.categories.cat.save', 'uses' =>'AdminCategoryController@saveCategory'));
    //feldolgozó kategória módosító form
    Route::any('/admin/categories/cat/edit/{type_id}/{id}',array('as'=>'admin.categories.cat.edit', 'uses' =>'AdminCategoryController@editCategory'));
     //kategória mentés
    Route::any('/admin/categories/cat/update/{id}',array('as'=>'admin.categories.cat.update', 'uses' =>'AdminCategoryController@updateCategory'));
    
    
    Route::get('/admin/menuitems/index',array('as'=>'admin.menuitems.index', 'uses' =>'AdminMenuitemController@index'));
    Route::post('/admin/menuitems/index',array('as'=>'admin.menuitems.postindex', 'uses' =>'AdminMenuitemController@index'));
    Route::resource('/admin/menuitems','AdminMenuitemController', array('except' => array('show')));    
});

Route::get('admin/login','AdminUserController@getLogin');
Route::post('admin/login','AdminUserController@postLogin');
Route::get('admin/logout',array('as'=>'admin.getLogout', 'uses'=>'AdminUserController@getLogout'));
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