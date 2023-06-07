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
            Route::post('/store-stats', 'StoreController')->name('store');
            Route::post('/delete-stats', 'DeleteController')->name('delete');
        });

        Route::group(['namespace' => 'Totals'], function () {
            Route::post('/general-stats', 'TotalGeneralTotalsController')->name('stats.general');
            Route::post('/available-seasons', 'TotalSeasonsController')->name('seasons');
            Route::post('/best-maps', 'TotalBestMapsController')->name('best.maps');
            Route::post('/maps-winrate', 'TotalMapWinrateController')->name('maps.winrate');
            Route::post('/races-winrate', 'TotalRacesWinrateController')->name('races.winrate');
            Route::post('/mmr-winrate', 'TotalMmrWinrateController')->name('mmr.winrate');
            Route::post('/map-race', 'TotalMapRaceController')->name('map.race');
            Route::post('/mmr-map-race', 'TotalMmrMapRaceController')->name('mmr.map.race');
        });

        Route::group(['namespace' => 'Excel'], function () {
            Route::post('/import-excel-file', 'ImportController')->name('import');
        });

        Route::group(['namespace' => 'Season', 'prefix' => 'seasons'], function () {
            Route::post('/', 'IndexController')->name('get.seasons');
        });

        Route::group(['namespace' => 'TotalValues', 'prefix' => 'total-values'], function () {
            Route::post('/update', 'StoreController')->name('totals.update');
        });

        Route::group(['namespace' => 'Filters', 'prefix' => 'filters'], function () {
            Route::post('/results', 'ResultsController')->name('get.results.list');
            Route::post('/maps', 'MapsController')->name('get.maps.list');
            Route::post('/races', 'RacesController')->name('get.races.list');
            Route::post('/find-logins', 'NicknameLoginController')->name('get.login');
        });
    });
});



