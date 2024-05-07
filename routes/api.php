<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LanguageController;

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

Route::controller(UserController::class)->prefix('auth')->middleware(['DbBackup', 'Localization'])->group(
    function () {
        //app()->setLocale('ar');
        Route::get('/users', 'index')->middleware(['auth:sanctum', 'admin']);
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
        Route::resource('posts', PostController::class)->middleware(['auth:sanctum']);
    }

);


/// switch language      ////////////////////////////////////////////////
Route::get('lang', function (Request $request) {
    $data = [
        'message' => trans('main.switched_success_language')
    ];
    return response()->json($data, 200);
})->middleware('Localization');

/// run migrate in server  ////////////////////////////////////////////////
Route::get('migrate', function () {
    Artisan::call('migrate', array('--force' => true));
    return "migrate is done";
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
