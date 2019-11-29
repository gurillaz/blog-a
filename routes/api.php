<?php

/*header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');*/

use App\Http\Controllers\ArticleController;
use Illuminate\Http\Request;
// use Symfony\Component\Routing\Route;

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



//Guest Routes
// Route::group(['prefix' => 'guest','middleware'=>['auth','isAdmin']], function(){
Route::group(['prefix' => 'guest'], function () {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    // Send reset password mail
    Route::post('reset-password', 'AuthController@sendPasswordResetLink');
    // handle reset password form process
    Route::post('reset/password/{token}', 'AuthController@callResetPassword');

    Route::get('search/data_autofill', 'GuestController@data_autofill');
    Route::get('search', 'GuestController@get_results');
    Route::get('home', 'GuestController@home');
    Route::get('article/slug/{slug}', 'GuestController@show_slug');

    Route::resource('category', 'CategoryController')->only('show');
    Route::resource('tag', 'TagController')->only(['show']);
    Route::resource('user', 'UserController')->only(['show']);
});


//Athenticated routes
Route::group(['prefix' => 'auth', 'middleware' => ['auth:api']], function () {

    //Auth related data
    Route::get('refresh', 'AuthController@refresh');
    Route::get('user', 'AuthController@user');
    Route::post('logout', 'AuthController@logout');



    Route::get('auth_user', 'UserController@auth_user');
    Route::put('edit/{user}', 'AuthController@update');
    Route::post('toggle_bookmark', 'AuthController@toggle_bookmark');

    Route::resource('comment', 'CommentController')->only(['store', 'destroy']);
    Route::resource('article', 'ArticleController')->only(['store', 'update', 'destroy', 'create', 'edit']);
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isAdmin']], function () {

    Route::get('admin_home', 'AdminController@admin_home');
    Route::get('featured_order', 'AdminController@featured_order');
    Route::post('save_featured_order', 'AdminController@save_featured_order');

    Route::get('pending_articles', 'ArticleController@pending_articles');
    Route::post('approve_article/{article}', 'ArticleController@approve_article');
    Route::post('deny_article/{article}', 'ArticleController@deny_article');
    Route::post('aprove_all_articles', 'ArticleController@aprove_all_articles');



    Route::get('pending_comments', 'CommentController@pending_comments');
    Route::post('approve_comment/{comment}', 'CommentController@approve_comment');
    Route::post('deny_comment/{comment}', 'CommentController@deny_comment');
    Route::post('aprove_all_comments', 'CommentController@aprove_all_comments');


    Route::post('make_admin/{user}', 'UserController@make_admin');





    Route::resource('comment', 'CommentController');
    Route::resource('article', 'ArticleController');
    Route::resource('user', 'UserController');
    Route::resource('category', 'CategoryController');
    Route::resource('tag', 'TagController');
});
