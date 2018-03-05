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

// Article
Route::get('/articles', 'ArticleController@getList');
Route::get('/articles/{id}', 'ArticleController@getByID');
Route::post('/articles', 'ArticleController@create');
Route::put('/articles', 'ArticleController@update');
Route::delete('/articles', 'ArticleController@delete');

// Comment
Route::get('/comments', 'CommentController@getListByArticleID');
Route::get('/comments/{id}', 'CommentController@getByID');
Route::post('/comments', 'CommentController@create');
Route::delete('/comments', 'CommentController@delete');

// JWT Auth
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('recover', 'AuthController@recover');
Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('/users/token', 'AuthController@getAuthUser');
    Route::get('logout', 'AuthController@logout');
});
