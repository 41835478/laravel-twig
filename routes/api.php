<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(["prefix" => "v1"], function () {
    Route::get('/readme', 'ReadmeController@index');
    Route::get("/test", "Admin\TestController@index");

    Route::get('/buckydrop', 'ReadmeController@buckydrop');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
        //商家注册 登陆
    });

    Route::get("/geo/country", "Admin\GeoController@country");
    Route::get("/geo/state", "Admin\GeoController@state");
    Route::get("/geo/city", "Admin\GeoController@city");

    //接收数据处理接口


// 商户相关接口
    Route::post("/business/login", "Admin\BusinessController@login");
    Route::post("/business/create", "Admin\BusinessController@create");
    Route::get("/business/check_domain", "Admin\BusinessController@checkDomain");
    Route::get("/business/check_email", "Admin\BusinessController@checkEmail");

    Route::group(["middleware" => "auth:api"], function () {
        //从其他平台推数据过来
        Route::post("/business/push_store", "Admin\BusinessController@pushStore");

        Route::resource("/business/address", "Admin\BusinessAddressController");
        //关于商户地址基本信息
        Route::get("/business/address", "Admin\BusinessAddressController@index");
        Route::post("/business/address", "Admin\BusinessAddressController@create");

        Route::post("/business/push_store", "Admin\BusinessController@pushStore");

        Route::get("/products/search", "Admin\ProductsController@search");
        Route::post("/products/push_store", "Admin\ProductsController@pushStore");
        Route::get("/products/index", "Admin\ProductsController@index");

        Route::post("/products/action", "Admin\ProductsController@action");

        //文章分类相关
        Route::resource('blogs', 'Admin\BlogsController', ['only' => ['index', 'store', 'show']]);
        Route::post("/blogs/action", "Admin\BlogsController@action");
        Route::post("/blogs/{id}", "Admin\BlogsController@update");

        //具体博客相关
        Route::resource('article', 'Admin\ArticlesController', ['only' => ['index', 'store','show']]);
        Route::post("/article/action", "Admin\ArticlesController@action");
        Route::post("/article/{id}", "Admin\ArticlesController@update");


        Route::get("/images/index", "Admin\ImagesController@index");
        Route::post("/images/upload", "Admin\ImagesController@upload");
        //集合相关
        Route::resource('collections', 'Admin\CollectionsController', ['only' => ['index', 'store', 'show']]);
        Route::post("collections/action", "Admin\CollectionsController@actions");
        Route::post("collections/{id}", "Admin\CollectionsController@update");

        //单页相关
        Route::resource('pages', 'Admin\PagesController', ['only' => ['index', 'store', 'show']]);
        Route::post("/pages/action", "Admin\PagesController@action");
        Route::post("/pages/{id}", "Admin\PagesController@update");

        //导航相关
        Route::get("/navigation/index", "Admin\NavigationController@index");
        Route::get("/menus/link_list", "Admin\MenusController@linkList");
        Route::resource('menus', 'Admin\MenusController', ['only' => ['index', 'store', 'show']]);
        Route::post("menus/action", "Admin\MenusController@actions");
        Route::post("menus/{id}", "Admin\MenusController@update");

        //运费设置相关
        Route::get("/freights/get_rate_quote", "Admin\BusinessFreightsController@getRateQuote");
        Route::get("/freights/get_country_list", "Admin\BusinessFreightsController@getCountryList");

        Route::get("/freights/index","Admin\BusinessFreightsController@index");
        Route::post("/freights","Admin\BusinessFreightsController@store");
        Route::get("/freights/{country_id}","Admin\BusinessFreightsController@show");
        Route::post("/freights/action", "Admin\BusinessFreightsController@action");

        //一般设置相关
        Route::get("/settings/get_currency_list", "Admin\SettingsController@getCurrency");
        Route::get("/settings/show", "Admin\SettingsController@show");
        Route::post("/settings/update", "Admin\SettingsController@update");




    });
});
