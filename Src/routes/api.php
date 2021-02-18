<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('signin', 'API\AuthController@login');
Route::post('signup', 'API\AuthController@register');

Route::post('changepwd', 'API\SettingController@changePwd');
Route::post('update', 'API\SettingController@update');

Route::post('initialize', 'API\SettingController@initialize');

Route::post('plans', 'API\PlanController@getPlanList');
Route::post('follow', 'API\PlanController@follow');

Route::post('follows', 'API\FollowController@getFollowList');

Route::post('airecommend', 'API\RecommendController@getPlanList');

Route::post('scores', 'API\ScoreController@getScoreList');