<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['auth', 'user'], 'as' => 'user.'], function (){
	//index and showing product
	Route::get('/home-user', 'User\UserHomeController@index')->name('home');
	Route::get('/user-show/{product}', 'User\UserHomeController@show')->name('show');
	//adding to cart
	Route::post('/user-add/{product}', 'User\UserHomeController@addProduct')->middleware('can:order product')->name('add');
	//viewing cart content
	Route::get('/user-cart', 'User\UserHomeController@viewCart')->name('cart');
	//removing product from cart
	Route::get('/user-cart/{product}', 'User\UserHomeController@removeProduct')->name('remove');
	//checking out
	Route::post('/user-checkout', 'User\UserHomeController@checkOut')->middleware('can:order product')->name('checkout');
	Route::get('/user-summary/{order}', 'User\UserSummaryController@show')->name('summary');
	//user orders
	Route::get('/user-orders', 'User\UserOrderController@index')->name('orders');
});

Route::group(['middleware' => ['auth', 'admin'], 'as' => 'admin.'], function (){
	Route::get('/home-admin', 'Admin\AdminHomeController@index')->name('home');
	//storing product
	Route::get('/admin-create', 'Admin\AdminHomeController@create')->middleware('can:addedit product')->name('store')->name('create');
	Route::post('/admin-store', 'Admin\AdminHomeController@store')->middleware('can:addedit product')->name('store');
	//editing product
	Route::get('/admin-sedit/{product}', 'Admin\AdminHomeController@edit')->name('edit');
	Route::put('/admin-edit/{product}', 'Admin\AdminHomeController@update')->middleware('can:addedit product')->name('update');
	//delete product
	Route::delete('/admin-delete/{product}', 'Admin\AdminHomeController@destroy')->middleware('can:delete product')->name('delete');

	Route::get('/create-userform', 'Admin\CreateUserController@create')->name('userform');
	Route::post('/usercreate', 'Admin\CreateUserController@store')->name('usercreate');

	Route::get('/create-roleform', 'Admin\CreateRoleController@create')->name('roleform');
	Route::post('/rolecreate', 'Admin\CreateRoleController@store')->name('rolecreate');

	Route::get('/manageadmin', 'Admin\ManageAdminController@index')->name('manadmin');
	Route::post('/showadmin', 'Admin\ManageAdminController@show')->name('showadmin');
	Route::put('/updateadmin/{admin}', 'Admin\ManageAdminController@update')->name('updateadmin');
	Route::delete('/deleteadmin/{admin}', 'Admin\ManageAdminController@destroy')->name('deleteadmin');

	Route::get('/manageroles', 'Admin\ManageRoleController@index')->name('manroles');
	Route::get('/showrole/{role}', 'Admin\ManageRoleController@show')->name('showrole');
	Route::put('/updaterole/{role}', 'Admin\ManageRoleController@update')->name('updaterole');
	Route::delete('/deleterole/{role}', 'Admin\ManageRoleController@destroy')->name('deleterole');


	Route::get('/manageuser', 'Admin\ManageUserController@index')->name('manuser');
	Route::post('/showuser', 'Admin\ManageUserController@show')->name('showuser');
	Route::put('/updateuser/{user}', 'Admin\ManageUserController@update')->name('updateuser');
	Route::delete('/deleteuser/{user}', 'Admin\ManageUserController@destroy')->name('deleteuser');

});