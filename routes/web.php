<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SearchRequestController;

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

Route::get('', fn () => \inertia('Home'));
Route::get('/start', [\App\Http\Controllers\HomeController::class, 'showConfigurator'])->name('start');

Route::post('start', [SearchRequestController::class, 'start']);
Route::get('search-requests/{id}', [SearchRequestController::class, 'show'])->name('search-requests.show')->middleware('signed');
Route::post('search-requests/{id}/toggle', [SearchRequestController::class, 'toggleSearchRequest']);

//require __DIR__.'/auth.php';
