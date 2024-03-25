<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\LocationController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth',
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
        'prefix' => 'locations',
        'middleware' => 'auth:api'
    ], function () {
        Route::get('/', [LocationController::class, 'index']);
        Route::get('/{id}', [LocationController::class, 'show']);
        Route::post('/', [LocationController::class, 'store']);
        Route::post('/edit/{id}', [LocationController::class, 'update']);
    }
);

Route::group([
        'prefix' => 'events',
        'middleware' => 'auth:api'
    ], function () {
        Route::get('/', [CalendarEventController::class, 'index']);
        Route::get('/by_location', [CalendarEventController::class, 'byLocations']);
        Route::get('/{eventId}', [CalendarEventController::class, 'show']);
        Route::post('/', [CalendarEventController::class, 'store']);
        Route::post('edit/{eventId}', [CalendarEventController::class, 'update']);
        Route::delete('/{eventId}', [CalendarEventController::class, 'destroy']);
    }
);