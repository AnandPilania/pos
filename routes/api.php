<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'API', 'as' => 'api.'], function () {

    // Routes for menu app
    Route::post('app/login', 'MenuAppController@login');
    Route::group(['middleware' => 'api-auth', 'prefix' => 'app'], function () {
        Route::post('get-categories', 'MenuAppController@getCategoriesByClient');
        Route::post('get-products', 'MenuAppController@getProductsByCategory');
        Route::post('get-product-detail', 'MenuAppController@getProductDetail');
        Route::post('get-client-detail', 'MenuAppController@getClientDetail');
    });

    //Routes for BackOffice
    Route::post('auth/login', 'AuthController@login');
    Route::group(['middleware' => 'api-auth'], function () {

        Route::prefix('auth')->group(function () {
            Route::get('/check-auth', 'AuthController@getUserAuth');
            Route::get('/me', 'AuthController@me');
            Route::get('/refresh', 'AuthController@refresh');
            Route::get('/logout', 'AuthController@logout');
        });

        Route::prefix('profile')->group(function () {
            Route::post('/update-menu-app-colors', 'ProfileController@updateMenuAppColors');
            Route::post('/update-menu-app-logo', 'ProfileController@updateMenuAppLogo');
        });

        Route::prefix('products')->group(function () {
            Route::get('/paging', 'ProductsController@getProductsWithPageInfo');
            Route::get('/get', 'ProductsController@getProductInfo');
            Route::post('/add', 'ProductsController@addProduct');
            Route::post('/update', 'ProductsController@updateProduct');
            Route::delete('/delete', 'ProductsController@deleteProducts');
            Route::post('/toggleActive', 'ProductsController@toggleActiveProduct');
            Route::post('/changeState', 'ProductsController@changeProductsState');
            Route::post('/allActivate', 'ProductsController@toggleProductAllVisible');
            Route::post('/allInactivate', 'ProductsController@toggleProductAllInvisible');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/paging', 'CategoryController@getCategoriesWithPageInfo');
            Route::get('/all', 'CategoryController@getAllCategoryList');
            Route::get('/get', 'CategoryController@getCategoryInfo');
            Route::post('/add', 'CategoryController@addCategory');
            Route::post('/update', 'CategoryController@updateCategory');
            Route::delete('/delete', 'CategoryController@deleteCategories');
            Route::post('/toggleActive', 'CategoryController@toggleActiveCategory');
            Route::post('/changeState', 'CategoryController@changeCategoriesState');
            Route::post('/allActivate', 'CategoryController@toggleCategoryAllVisible');
            Route::post('/allInactivate', 'CategoryController@toggleCategoryAllInvisible');
        });
    });
});
