<?php

use App\Http\Controllers\admin\AdminController;
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

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login')->middleware('menu:admin');
Route::post('/login-store', [AuthenticatedSessionController::class, 'store'])->name('login.perform');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register-store', [RegisteredUserController::class, 'store'])->name('register.perform');

Route::group(['middleware' => ['auth', 'menu:admin']], function () {
    Route::get('/', fn () => redirect('home'));
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/admin-profile', [AdminController::class, 'adminMyProfile'])->name('admin.profile');
    Route::get('/users', [AdminController::class, 'allUsersView'])->name('users.all.view');
    Route::get('/users-create', [AdminController::class, 'createUserView'])->name('users.create.view');
    Route::post('/users-create', [AdminController::class, 'storeUser'])->name('new.user.store');
    Route::post('/users/self/update', [AdminController::class, 'updateSelf'])->name('user.self.update');
    Route::post('/users/self/update-avatar', [AdminController::class, 'updateSelfAvatar'])->name('user.self.update.avatar');

    Route::get('/requests/new-coach', [AdminController::class, 'newCoachRequestsView'])->name('requests.new.coach');
    Route::get('/requests/premium', [AdminController::class, 'permiumRequestsView'])->name('requests.premium');

    Route::post('/requests/coach/change-status', [AdminController::class, 'changeStatusCoachRequest'])->name('admin.coach.request.change-status');
    Route::post('/requests/premium/change-status', [AdminController::class, 'changeStatusPremiumRequest'])->name('admin.permium.request.change-status');
    
    Route::post('/payment/qr/change', [AdminController::class, 'changeQR'])->name('admin.qr.update');
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
    return view('pages.tables');
})->name('page')->middleware('menu:admin');
Route::get('/virtual-reality', function () {
    return view('pages.virtual-reality');
})->name('virtual-reality');
Route::get('/rtl', function () {
    return view('pages.rtl');
})->name('rtl')->middleware('menu:admin');
