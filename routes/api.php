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


Route::get('article/slug/{slug}', 'ArticleController@show_slug');
Route::resource('article', 'ArticleController');
Route::resource('user', 'UserController');
Route::resource('category', 'CategoryController');
Route::resource('comment', 'CommentController');
Route::resource('tag', 'TagController');
Route::get('search/data_autofill', 'SearchController@data_autofill');
Route::get('search', 'SearchController@get_results');

Route::group(['middleware' => 'auth'], function () {
    Route::get('auth_user', 'UserController@auth_user');
});

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::put('edit/{user}', 'AuthController@update');
        Route::post('logout', 'AuthController@logout');
        Route::post('toggle_bookmark', 'AuthController@toggle_bookmark');
    });
    //Auth related data

    // Send reset password mail
    Route::post('reset-password', 'AuthController@sendPasswordResetLink');
    // handle reset password form process
    Route::post('reset/password/{token}', 'AuthController@callResetPassword');
});




// Route::group(['middleware' => 'auth:api'], function(){
//     // Users
//     Route::get('users', 'UserController@index')->middleware('isAdmin');
//     Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');
// });
