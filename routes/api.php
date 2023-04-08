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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

    Route::group(['middleware' => 'auth:api'], function () {

        Route::group(['namespace' => 'GameStat'], function () {
            Route::post('/index', 'IndexController')->name('get');
            //Route::post('/filter', 'IndexController')->name('get');
            Route::post('/store-stats', 'StoreController')->name('store');
            Route::post('/delete-stats', 'DeleteController')->name('delete');
        });

        Route::group(['namespace' => 'Excel'], function () {
            Route::post('/import-excel-file', 'ImportController')->name('import');
        });

        Route::group(['namespace' => 'Season', 'prefix' => 'seasons'], function () {
            Route::post('/', 'IndexController')->name('get-seasons');
        });

        Route::group(['namespace' => 'filters', 'prefix' => 'filters'], function () {
            Route::post('/results', 'ResultsController')->name('get-results-list');
            Route::post('/maps', 'MapsController')->name('get-maps-list');
            Route::post('/races', 'RacesController')->name('get-races-list');
            Route::post('/find-logins', 'NicknameLoginController')->name('get-login');
        });
    });
});



