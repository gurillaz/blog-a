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
Route::group(['middleware' => ['jwt.auth', 'isAdmin']], function () {


Route::get('tags/export/', 'TagController@export');
Route::get('comments/export/', 'CommentController@export');
Route::get('articles/export/', 'ArticleController@export');
Route::get('categories/export/', 'CategoryController@export');
Route::get('users/export/', 'UserController@export');
});

// Route to handle page reload in Vue except for api routes
Route::get('/{any?}', function (){
    return view('welcome');
})->where('any', '^(?!api\/)[\/\w\.-]*');