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
	Route::post('/user-add/{product}', 'User\UserHomeController@addProduct')->name('add');
	//viewing cart content
	Route::get('/user-cart', 'User\UserHomeController@viewCart')->name('cart');
	//removing product from cart
	Route::get('/user-cart/{product}', 'User\UserHomeController@removeProduct')->name('remove');
	//checking out
	Route::post('/user-checkout', 'User\UserHomeController@checkOut')->name('checkout');
	Route::get('/user-summary/{order}', 'User\UserSummaryController@show')->name('summary');
	//user orders
	Route::get('/user-orders', 'User\UserOrderController@index')->name('orders');
});

Route::group(['middleware' => ['auth', 'admin'], 'as' => 'admin.'], function (){
	Route::get('/home-admin', 'Admin\AdminHomeController@index')->name('home');
	//storing product
	Route::get('/admin-create', 'Admin\AdminHomeController@create')->name('create');
	Route::post('/admin-store', 'Admin\AdminHomeController@store')->name('store');
	//editing product
	Route::get('/admin-sedit/{product}', 'Admin\AdminHomeController@edit')->name('edit');
	Route::put('/admin-edit/{product}', 'Admin\AdminHomeController@update')->name('update');
	//delete product
	Route::delete('/admin-delete/{product}', 'Admin\AdminHomeController@destroy')->name('delete');

	Route::get('/create-userform', 'Admin\CreateUserController@create')->name('userform');
	Route::post('/usercreate', 'Admin\CreateUserController@store')->name('usercreate');

	Route::get('/create-roleform', 'Admin\CreateRoleController@create')->name('roleform');
	Route::post('/rolecreate', 'Admin\CreateRoleController@store')->name('rolecreate');

	Route::get('/manageusers', 'Admin\ManageUserController@index')->name('manusers');
	Route::post('/showuser', 'Admin\ManageUserController@show')->name('showuser');
	Route::put('/updateuser/{user}', 'Admin\ManageUserController@update')->name('updateuser');
	Route::delete('/deleteuser/{user}', 'Admin\ManageUserController@destroy')->name('deleteuser');

});