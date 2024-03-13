<?php

use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\user\AuthenticatedSessionController;
use App\Http\Controllers\user\RegisteredUserController;
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

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login-store', [AuthenticatedSessionController::class, 'store'])->name('login.perform');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register-store', [RegisteredUserController::class, 'store'])->name('register.perform');

Route::group(['middleware' => ['auth', 'menu:admin']], function () {
    Route::get('/', fn () => redirect('home'));
    Route::get('/home', [HomeController::class, 'home'])->name('home');
});



Route::get('/sign-in-static', function () {
    return view('pages.sign-in-static');
})->name('sign-in-static');

Route::get('/profile-static', function () {
    return view('pages.profile-static');
})->name('profile-static');
Route::get('/sign-up-static', function () {
    return view('pages.sign-up-static');
})->name('sign-up-static');
Route::get('/profile', function () {
    return view('pages.profile');
})->name('profile');
Route::get('/page', function () {
    return view('pages.page');
})->name('page');
Route::get('/virtual-reality', function () {
    return view('pages.virtual-reality');
})->name('virtual-reality');
Route::get('/rtl', function () {
    return view('pages.rtl');
})->name('rtl');
