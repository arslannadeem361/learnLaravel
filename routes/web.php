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


/*Route::get('/', function () {
    return view('welcome');
});*/

// public routes
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/index', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::post('login',  [App\Http\Controllers\HomeController::class, 'login'])->name('login');
Route::get('/register', [App\Http\Controllers\HomeController::class, 'register'])->name('register');
Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


Auth::routes();
Route::group(['middleware' => ['prevent-back-history','auth']],function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
    Route::resource('categories', 'CategoriesController');
    Route::get('/edit-category/{id?}','CategoriesController@edit')->name('category_edit');
    Route::POST('/update-category/{id?}','CategoriesController@update')->name('category_update');
    Route::POST('/delete-category','CategoriesController@destroy')->name('category_delete');

    Route::resource('products', 'ProductsController');
    Route::get('/get-sub-categories/{id}', [App\Http\Controllers\ProductsController::class, 'getSubCategories']);
    Route::get('/delete-edit-image/{id}', [App\Http\Controllers\ProductsController::class, 'removeImage']);
    Route::get('/delete-product/{id}', [App\Http\Controllers\ProductsController::class, 'destroy']);

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::get('/edit-role/{id?}','RoleController@edit')->name('role_edit');
    Route::POST('/delete-role','RoleController@destroy')->name('role_delete');

    Route::resource('permission', PermissionController::class);
});
