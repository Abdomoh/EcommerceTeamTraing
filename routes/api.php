<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RouteRegistrar;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Artisan;

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

Route::controller(UserController::class)->prefix('auth')->middleware(['DbBackup'])->group(
    function () {
        Route::get('/users','index')->middleware(['auth:sanctum', 'admin']);
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
    }
);

Route::get('migrate', function () {
    Artisan::call('migrate', array('--force' => true));
    return "migrate is done";
});





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
