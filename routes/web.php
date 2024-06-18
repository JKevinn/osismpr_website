<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function() {
    return view('index');
})->name('login');

Route::post('/authLogin', [AuthController::class, "login"])->name('authLogin');
Route::post('/logout', [AuthController::class, "logout"])->name("logout");

Route::middleware("UserAuthCheck")->group(function() {

    Route::get('/dashboard', [DashboardController::class, "index"])->name("dashboard");
    
    Route::prefix("user")->name("user.")->group(function() {
        Route::get('/', [UserController::class, "index"])->name("index");
        Route::post('/store', [UserController::class, "store"])->name("store");
        Route::put('/update/{uuid}', [UserController::class, "update"])->name("update");
        Route::put('/editProfile/{uuid}', [UserController::class, "editProfile"])->name("editProfile");
        Route::delete('/delete/{uuid}', [UserController::class, "delete"])->name("delete");
    });
    
    Route::prefix("schedule")->name("schedule.")->group(function() {
        Route::get('/', [ScheduleController::class, "index"])->name("index");
        Route::post('/store', [ScheduleController::class, "store"])->name("store");
        Route::put('/update/{uuid}', [ScheduleController::class, "update"])->name("update");
        Route::delete('/delete/{uuid}', [ScheduleController::class, "delete"])->name("delete");
    });
    
    Route::prefix("attendance")->name("attendance.")->group(function() {
        Route::get('/', [AttendanceController::class, "index"])->name("index");
        Route::post('/scan/{meeting_uuid}', [AttendanceController::class, "store"])->name("scan");
        Route::get('/scanner/{meeting_uuid}', [AttendanceController::class, "scanner"])->name("scanner");
        Route::get('/attendance/{meeting_uuid}',[AttendanceController::class, "listAttendance"])->name("listAttendance");
    });
});