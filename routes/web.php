<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocietyController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/check-username-availability/{username}', [RegisterController::class, 'checkUsernameAvailability']);

Route::middleware('auth')->group(function () {
    Route::get('/societies', [SocietyController::class, 'index'])->name('societies');  
    Route::get('/societies/{id}', [SocietyController::class, 'viewSocietyInfo'])->name('view-society');
    Route::post('/create-society', [SocietyController::class, 'createSociety'])->name('create-society');
    Route::post('/join-society/{societyId}', [SocietyController::class, 'joinSociety'])->name('join-society');
    Route::post('/leave-society/{societyId}', [SocietyController::class, 'leaveSociety'])->name('leave-society');

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/change-email', [AccountController::class, 'changeEmail'])->name('change-email');
    Route::post('/change-password', [AccountController::class, 'changePassword'])->name('change-password');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});


