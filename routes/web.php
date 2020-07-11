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


use Illuminate\Support\Facades\Route;

Route::namespace('App')->group(function () {
    Route::get('/', 'AppController@showHomePage');
    Route::get('/login', 'AppController@showLoginPage');
    Route::get('/products', 'AppController@showProductsPage');
    Route::get('/contact', 'AppController@showContactPage');
    Route::get('/restaurant/{customer_id}', 'AppController@showProductPage');
    Route::post('/product/get', 'AppController@getProductDetail');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::get('/', 'AdminController@index')->name('home');
    Route::post('/register', 'AuthController@register')->name('register');
    Route::get('/login', 'AuthController@showLoginPage')->name('login.show');
    Route::post('/login', 'AuthController@login')->name('login');
    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::get('/auth/token', 'AuthController@token');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        Route::group(['prefix' => 'employees', 'as' => 'employees.'], function () {
            Route::get('/', 'EmployeesController@index')->name('show');
            Route::get('/add', 'EmployeesController@showAddPage')->name('add.show');
            Route::post('/add', 'EmployeesController@add')->name('add');
            Route::get('/edit/{id}', 'EmployeesController@showEditPage')->name('edit.show');
            Route::post('/edit', 'EmployeesController@edit')->name('edit');
            Route::post('/del', 'EmployeesController@destroy')->name('delete');
            Route::post('/toggle-active', 'EmployeesController@toggleActive')->name('toggle-active');
        });

        Route::group(['prefix' => 'positions', 'as' => 'positions.'], function () {
            Route::get('/', 'PositionsController@index')->name('show');
            Route::get('/add', 'PositionsController@showAddPage')->name('add.show');
            Route::post('/add', 'PositionsController@add')->name('add');
            Route::get('/edit/{id}', 'PositionsController@showEditPage')->name('edit.show');
            Route::post('/edit', 'PositionsController@edit')->name('edit');
            Route::post('/del', 'PositionsController@destroy')->name('delete');
        });

        // Permission *** not have UI, just for PostMan
        Route::get('/permissions/get', 'PermissionsController@get');
        Route::post('/permissions/add', 'PermissionsController@add');

        Route::group(['prefix' => 'clients', 'as' => 'clients.'], function () {
            Route::get('/', 'ClientsController@index')->name('show');
            Route::get('/add', 'ClientsController@showAddPage')->name('add.show');
            Route::post('/add', 'ClientsController@add')->name('add');
            Route::get('/edit/{id}', 'ClientsController@showEditPage')->name('edit.show');
            Route::post('/edit', 'ClientsController@edit')->name('edit');
            Route::post('/del', 'ClientsController@destroy')->name('delete');
            Route::post('/toggle-active', 'ClientsController@toggleActive')->name('toggle-active');

            Route::group(['prefix' => '{client_id}'], function () {
                Route::get('/', 'ClientsController@showDetailPage')->name('detail.show');

                Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
                    Route::get('/', 'ProductsController@index')->name('show');

                    Route::get('/edit/{id}', 'AdminController@showProductEditPage');
                    Route::post('/edit', 'AdminController@editProduct');
                    Route::post('/del', 'AdminController@delProduct');
                    Route::get('/{id}', 'AdminController@showProductDetailPage');
                    Route::post('/toggle-visible', 'AdminController@toggleProductVisible');
                    Route::get('/show-all', 'AdminController@toggleProductAllVisible');
                    Route::get('/hide-all', 'AdminController@toggleProductAllInvisible');

                    Route::get('/{customer_id}', 'AdminController@showProductsPage');
                    Route::get('/{customer_id}/add', 'AdminController@showProductAddPage');
                    Route::post('/{customer_id}/add', 'AdminController@addProduct');

                });

                Route::prefix('categories')->group(function () {

                    Route::get('/', 'AdminController@showCategoryFirstPage');

                    Route::get('/edit/{id}', 'AdminController@showCategoryEditPage');
                    Route::get('/edit', 'AdminController@showCategoriesPage');
                    Route::post('/edit', 'AdminController@editCategory');
                    Route::post('/del', 'AdminController@delCategory');
                    Route::get('/detail/{id}', 'AdminController@showCategoryDetailPage');
                    Route::post('/toggle-visible', 'AdminController@toggleCategoryVisible');
                    Route::get('/show-all', 'AdminController@toggleCategoryAllVisible');
                    Route::get('/hide-all', 'AdminController@toggleCategoryAllInvisible');

                    Route::get('/{customer_id}', 'AdminController@showCategoriesPage');
                    Route::get('/{customer_id}/add', 'AdminController@showCategoryAddPage');
                    Route::post('/{customer_id}/add', 'AdminController@addCategory');

                });
            });

            Route::post('/toggle-add-product', 'AdminController@toggleCustomerAddProduct');
            Route::get('/print-invoice/{id}', 'AdminController@showCustomerInvoicePrintPreviewPage');
            Route::get('/print-invoice/{id}/print', 'AdminController@printCustomerInvoice');
            Route::post('/resuscitate-customer', 'AdminController@resuscitateCustomer');
        });



        Route::get('/profile', 'AdminController@showProfilePage')->name('profile.show');
        Route::post('/profile/edit', 'AdminController@editProfile')->name('profile.edit');
    });

    Route::middleware('customer-auth')->group(function (){
        Route::get('/my-page', 'AdminController@showMyPage');

        Route::get('/design', 'AdminController@showDesignPage');
        Route::post('/design/edit', 'AdminController@editDesign');

    });

    Route::middleware('user-auth')->group(function (){

        //Route::get('/dashboard', 'AdminController@dashboard');
        Route::post('/set-client-to-session', 'AdminController@setClientIdToSession');
    });

});
