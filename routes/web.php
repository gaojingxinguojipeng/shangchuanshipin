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

Route::get('/', function () {

    return view('welcome');
});
Route::get("vedio","Oss\OssController@vedio");
Route::get("vedioList","Oss\OssController@vedioList");
Route::get("ceshi","Oss\OssController@ceshi");
Route::get("zhuan","Oss\OssController@zhuan");
Route::post("oss","Oss\OssController@oss");
Route::get("choua","Chou\ChouController@choua");
Route::any("jiang","Chou\ChouController@jiang");
Route::any("zhuzhu","Chou\ChouController@zhuzhu");
Route::any("insert","Chou\ChouController@insert");
Route::any("curl","Weixin\WeixinController@curl");
Route::any("open","Weixin\WeixinController@open");

Route::any("goodslist","Goods\GoodsController@goodslist");
Route::any("cart","Goods\GoodsController@cart");
Route::any("update_num","Goods\GoodsController@update_num");
Route::any("order","Goods\GoodsController@order");
Route::any("orderlist","Goods\GoodsController@orderlist");

Route::any("sign","Sign\SignController@sign");









