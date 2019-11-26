<?php

/*header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');*/

use App\Http\Controllers\ArticleController;
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

Route::get('search/data_autofill', 'SearchController@data_autofill');
Route::get('search', 'SearchController@get_results');
Route::get('home', 'WebClientController@home');
Route::get('admin_home', 'WebClientController@admin_home');

Route::get('pending_articles', 'WebClientController@pending_articles');
Route::post('approve_article/{article}', 'WebClientController@approve_article');
Route::post('deny_article/{article}', 'WebClientController@deny_article');
Route::post('aprove_all_articles', 'WebClientController@aprove_all_articles');

Route::get('pending_comments', 'WebClientController@pending_comments');
Route::post('approve_comment/{comment}', 'WebClientController@approve_comment');
Route::post('deny_comment/{comment}', 'WebClientController@deny_comment');
Route::post('aprove_all_comments', 'WebClientController@aprove_all_comments');

Route::get('article/slug/{slug}', 'ArticleController@show_slug');

Route::resource('article', 'ArticleController');
Route::resource('user', 'UserController');
Route::resource('category', 'CategoryController');
Route::resource('comment', 'CommentController');
Route::resource('tag', 'TagController');


Route::get('auth_user', 'UserController@auth_user');
Route::put('edit/{user}', 'AuthController@update');
Route::post('toggle_bookmark', 'AuthController@toggle_bookmark');

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');


    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');

        Route::post('logout', 'AuthController@logout');
    });
    //Auth related data

    // Send reset password mail
    Route::post('reset-password', 'AuthController@sendPasswordResetLink');
    // handle reset password form process
    Route::post('reset/password/{token}', 'AuthController@callResetPassword');
});
