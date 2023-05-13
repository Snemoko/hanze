<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Models\Visit;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


// Users need to be registered to view these routes
Route::group(['middleware' => 'auth'], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Visits
    Route::get("/visits", [VisitController::class, 'index'])->name('visit-list');
    Route::get('/visit/{visit}', [VisitController::class, 'retrieveVisit'])->name('visit-info');
    Route::post('/visit/{visit}', [VisitController::class, 'update'])->name('visit-update');

    // Users
    Route::get('/user/{user}', [UserController::class, 'retrieveUser'])->name('user-info');

});

// API

Route::post('/api/tokens/create', [ApiController::class, 'issueToken'])->name('api-createToken');
route::middleware('auth:sanctum')->get("/api/retrieve/list", [ApiController::class, 'retrieveList'])->name('api-retrieveList');
