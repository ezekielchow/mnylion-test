<?php

use App\Http\Controllers\FeatureController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('feature')->group(function () {
    Route::get('/', [FeatureController::class, 'checkAccess'])->name('feature.checkAccess');
    Route::post('/', [FeatureController::class, 'update'])->name('feature.update');
});
