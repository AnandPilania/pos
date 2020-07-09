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

// Routes for menu app
Route::prefix('auth')->group(function () {
    Route::post('login', 'APIController@doLogin');
});

Route::middleware('api-auth')->group(function (){

    Route::post('get-categories', 'APIController@getCategoriesByClient');
    Route::post('get-products', 'APIController@getProductsByCategory');
    Route::post('get-product-detail', 'APIController@getProductDetail');
    Route::post('get-client-detail', 'APIController@getClientDetail');

});

// Routes for BO
Route::prefix('user')->group(function () {
    Route::post('login', 'APIController@userLogin');
});

Route::middleware('api-auth')->group(function (){

    Route::prefix('user')->group(function () {
        Route::get('/check-auth', 'APIController@getUserAuth');
        Route::post('/update-menu-app-colors', 'APIController@updateMenuAppColors');
        Route::post('/update-menu-app-logo', 'APIController@updateMenuAppLogo');
    });

    Route::prefix('products')->group(function () {
        Route::get('/paging', 'APIController@getProductsWithPageInfo');
        Route::get('/get', 'APIController@getProductInfo');
        Route::post('/add', 'APIController@addProduct');
        Route::post('/update', 'APIController@updateProduct');
        Route::delete('/delete', 'APIController@deleteProducts');
        Route::post('/toggleActive', 'APIController@toggleActiveProduct');
        Route::post('/changeState', 'APIController@changeProductsState');
        Route::post('/allActivate', 'APIController@toggleProductAllVisible');
        Route::post('/allInactivate', 'APIController@toggleProductAllInvisible');
    });

    Route::prefix('categories')->group(function () {
        Route::get('/paging', 'APIController@getCategoriesWithPageInfo');
        Route::get('/all', 'APIController@getAllCategoryList');
        Route::get('/get', 'APIController@getCategoryInfo');
        Route::post('/add', 'APIController@addCategory');
        Route::post('/update', 'APIController@updateCategory');
        Route::delete('/delete', 'APIController@deleteCategories');
        Route::post('/toggleActive', 'APIController@toggleActiveCategory');
        Route::post('/changeState', 'APIController@changeCategoriesState');
        Route::post('/allActivate', 'APIController@toggleCategoryAllVisible');
        Route::post('/allInactivate', 'APIController@toggleCategoryAllInvisible');
    });
});
