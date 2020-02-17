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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function() {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('/login', 'AuthController@login');
        Route::group(['middleware' => 'auth'], function() {
            Route::get('/logout/{token}', 'AuthController@logout');
        });
    });
    Route::group(['middleware' => 'admin'], function() {
        Route::resource('branches', 'BranchController');
        Route::resource('studios', 'StudioController');
        Route::resource('schedules', 'SchedulesController');
        Route::resource('movies', 'MovieController');
        Route::get('available-schedules', 'SchedulesController@schedules');
    });
});
