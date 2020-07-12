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

Route::group(['prefix' => env('ADMIN_PREFIX'), 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::get('/', 'AdminController@index')->name('home');
    Route::post('/register', 'AuthController@register')->name('register');
    Route::get('/login', 'AuthController@showLoginPage')->name('login.show');
    Route::post('/login', 'AuthController@login')->name('login');
    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::get('/auth/token', 'AuthController@token');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        // Employees
        Route::group(['prefix' => 'employees', 'as' => 'employees.'], function () {
            Route::get('/', 'EmployeesController@index')->name('show');
            Route::get('/add', 'EmployeesController@showAddPage')->name('add.show');
            Route::post('/add', 'EmployeesController@add')->name('add');
            Route::post('/delete', 'EmployeesController@delete')->name('delete');
            Route::post('/toggle-active', 'EmployeesController@toggleActive')->name('toggle-active');
            Route::get('/{id}', 'EmployeesController@showEditPage')->name('edit.show');
            Route::post('/{id}', 'EmployeesController@edit')->name('edit');
        });

        // Positions
        Route::group(['prefix' => 'positions', 'as' => 'positions.'], function () {
            Route::get('/', 'PositionsController@index')->name('show');
            Route::get('/add', 'PositionsController@showAddPage')->name('add.show');
            Route::post('/add', 'PositionsController@add')->name('add');
            Route::post('/delete', 'PositionsController@delete')->name('delete');
            Route::get('/{id}', 'PositionsController@showEditPage')->name('edit.show');
            Route::post('/{id}', 'PositionsController@edit')->name('edit');
        });

        // Permission *** not have UI, just for PostMan
        Route::get('/permissions/get', 'PermissionsController@get');
        Route::post('/permissions/add', 'PermissionsController@add');

        // Clients
        Route::group(['prefix' => 'clients', 'as' => 'clients.'], function () {
            Route::get('/', 'ClientsController@index')->middleware('can:client-list')->name('show');
            Route::get('/add', 'ClientsController@showAddPage')->name('add.show');
            Route::post('/add', 'ClientsController@add')->name('add');
            Route::get('/edit/{id}', 'ClientsController@showEditPage')->name('edit.show');
            Route::post('/edit', 'ClientsController@edit')->name('edit');
            Route::post('/delete', 'ClientsController@destroy')->name('delete');
            Route::post('/toggle-active', 'ClientsController@toggleActive')->name('toggle-active');

            Route::group(['prefix' => '{client_id}'], function () {
                Route::get('/', 'ClientsController@showDetailPage')->name('detail.show');

                // Products
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

                // Categories
                Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
                    Route::get('/', 'CategoriesController@index')->name('show');

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

        // Business Types
        Route::group(['prefix' => 'business-types', 'as' => 'business-types.'], function () {
            Route::get('/', 'BusinessTypesController@index')->name('show');
            Route::get('/getList', 'BusinessTypesController@getBusinessTypeList')->name('list');
            Route::get('/add', 'BusinessTypesController@showAddPage')->name('add.show');
            Route::post('/add', 'BusinessTypesController@add')->name('add');
            Route::post('/delete', 'BusinessTypesController@delete')->name('delete');
            Route::get('/{id}', 'BusinessTypesController@showEditPage')->name('edit.show');
            Route::post('/{id}', 'BusinessTypesController@edit')->name('edit');
        });


        Route::get('/profile', 'AuthController@showProfilePage')->name('profile.show');
        Route::post('/profile/edit', 'AuthController@editProfile')->name('profile.edit');
    });




        Route::get('/my-page', 'AdminController@showMyPage');
        Route::get('/design', 'AdminController@showDesignPage');
        Route::post('/design/edit', 'AdminController@editDesign');
        Route::post('/set-client-to-session', 'AdminController@setClientIdToSession');


});
