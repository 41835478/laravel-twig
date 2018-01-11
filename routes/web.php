<?php

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
Route::group([], function() {

    Route::get('/', "HomeController@index")->name('home');
    Route::get('/products/{id}', "ProductsController@show")->name('product_show');
//    Route::get('/collections', "HomeController@collections")->name('product');

     // 账号相关
    Route::get('/account/register', "AccountsController@create")->name('account_create');
    Route::post('/account/register', "AccountsController@register")->name('account_create_post');
    Route::get('/account/login', "AccountsController@login")->name('account_login_get');
    Route::post('/account/login', "AccountsController@login")->name('account_login_post');
    Route::get('/account/logout', "AccountsController@logout")->name('account_logout');

    Route::get('/account', "AccountsController@show")->name('account_show');

    //账户管理地址相关
    Route::get('/account/addresses', "AddressController@index")->name('account_address_index');
    Route::post('/account/addresses', "AddressController@store")->name('account_address_create');
    Route::post('/account/addresses/{id}', "AddressController@update")->name('account_address_update');
    Route::delete("/account/addresses/{id}","AddressController@destroy")->name("account_address_delete");

    //c端订单相关处理

    Route::get('/account/orders', "OrdersController@index")->name('account_orders_index');


    // 购物车相关

    Route::get('/carts', "CartsController@index")->name('cart_index');
    Route::get('/carts/change', "CartsController@change")->name('cart_change');
    Route::post('/carts/add', "CartsController@store")->name('cart_add');
    Route::post('/carts', "CartsController@checkout")->name('cart_checkout');

    //集合相关
    Route::get('/collections/{id}', "CollectionsController@show")->name('product_show');
    Route::get('/collection/all', "CollectionsController@all")->name('product_all');

    Route::get('/collections/{collection_id}/products/{product_id}', "ProductsController@collectionProductInfo")->name('collection_product_show');

    //单页相关
    Route::get('/pages/{id}', "PagesController@show")->name('pages_show');

    //留言相关
    Route::post('/contact', "ContactsController@store")->name('contact_store');

    Route::get('/checkouts/{sign}', "CheckoutController@index")->name('checkout_get');
    Route::post('/checkouts/{sign}', "CheckoutController@index")->name('checkout_post');


    Route::group(['prefix' => 'admin'], function() {
        Route::get('/',"Auth\LoginController@showLoginForm");
    });

    //paypal 支付相关

    Route::post('/paypal/create-payment', "PayPalController@create")->name('paypal_create');

    Route::post('/paypal/execute-payment', "PayPalController@executePayment")->name('paypal_create_post');

    Route::get('/paypal/execute-payment', "PayPalController@executePayment")->name('paypal_create_get');


});

Route::get('test',function (){
    $users = \App\User::all();
    return view('index',compact('users'));
});

Auth::routes();


