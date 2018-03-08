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

Route::group(['domain' => env('API_DOMAIN')], function () {
    // auth
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

        Route::post('signup', 'AuthController@signup');
        Route::post('login', 'AuthController@login');
        
        Route::group(['middleware' => 'jwt.auth'], function() {
            Route::get('me', 'AuthController@getAuthenticatedUser');
            Route::get('profile', 'MeController@getProfile');
            Route::post('me', 'MeController@update');
        });

        // socials
        Route::post('facebook', 'SocialController@facebook');
        Route::post('google', 'SocialController@google');
        Route::post('twitter', 'SocialController@twitter');

    });
    // admin
    Route::group([
        'prefix' => 'admin',
        'middleware' => ['jwt.auth', 'admin'],
        'namespace' => 'Admin',
    ], function () {

        Route::get('dashboard', 'DashboardController@index');
        Route::get('export', 'DashboardController@export');
        
        Route::resource('user', 'UserController', [
            'only' => [
                'index', 
                'show', 
                'destroy',
            ],
        ]);

        Route::resource('product', 'ProductController', [
            'only' => [
                'index', 
                'store', 
                'show', 
                'destroy',
            ],
        ]);
        Route::post('product/{id}', 'ProductController@update');
        Route::post('import-products', 'ProductController@import');
        
        Route::resource('category', 'CategoryController', [
            'only' => [
                'index', 
                'store', 
                'show', 
                'update', 
                'destroy',
            ],
        ]);

        Route::resource('suggest', 'SuggestController', [
            'only' => [
                'index', 
                'show', 
                'update', 
                'destroy',
            ],
        ]);

        Route::resource('order', 'OrderController', [
            'only' => [
                'index', 
                'show', 
                'update', 
                'destroy',
            ],
        ]);
        
    });
    // home
    Route::group(['namespace' => 'Home'], function() {
        Route::get('category', 'HomeController@getCategories');
        Route::get('products-new', 'HomeController@getNewProducts');
        Route::get('products-recently', 'HomeController@getProductsRecently');
        Route::get('search', 'HomeController@getProductsSearch');
        Route::get('{category_slug}', 'HomeController@getProductsByCategory');
        Route::get('product/{name}', 'HomeController@getProduct');
        Route::post('suggest', 'SuggestController@store');
        Route::post('order', 'OrderController@store');
        Route::get('order/{id}', 'OrderController@show');
        Route::post('rate', 'RateController@store');
        Route::get('comment/{name}', 'CommentController@index');
        Route::post('comment/{name}', 'CommentController@store');
        Route::put('comment/{id}', 'CommentController@update');
        Route::delete('comment/{id}', 'CommentController@destroy');
    });
});

Route::get('/', function () {
    return view('index');
});
