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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('jwt.auth')->get('/users', function (Request $request) {
    return auth()->user();
});



Route::group(['prefix' => 'v1','namespace'=>'API\V1'], function() {

    /*
    |--------------------------------------------------------------------------
    | API Routes For User
    */

    Route::post('refresh/token','AuthController@refreshToken');

    Route::group(['prefix' => 'user'], function(){ // this to add user after v1 in url

            Route::post('/register', 'AuthController@register');
            Route::post('/login', 'AuthController@login');


            Route::group(['middleware' => 'jwt.auth', 'jwt.refresh'], function() {

                Route::get('/profile', 'UserController@Profile');
                Route::get('/tweets', 'UserController@Tweets'); // current user tweets
                Route::post('/add/tweet', 'UserController@StoreTweet');
                Route::post('/follow/user', 'UserController@FollowUser');
                Route::post('/unfollow/user', 'UserController@UnFollowUser');
                Route::get('/all/users', 'UserController@AllUsers');
                Route::get('/timeline', 'UserController@TimeLine');

            });


    });

  
});
