<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'menu:admin', 'type:admin'], 'prefix' => 'admin'], function () {
    Route::get('/', fn () => redirect('home'));
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    Route::get('/admin-profile', [AdminController::class, 'adminMyProfile'])->name('admin.profile');
    Route::get('/users', [AdminController::class, 'allUsersView'])->name('users.all.view');
    Route::get('/users-create', [AdminController::class, 'createUserView'])->name('users.create.view');
    Route::post('/users-create', [AdminController::class, 'storeUser'])->name('new.user.store');

    Route::get('/requests/new-coach', [AdminController::class, 'newCoachRequestsView'])->name('requests.new.coach');
    Route::get('/requests/premium', [AdminController::class, 'permiumRequestsView'])->name('requests.premium');
    Route::get('/requests/meal', [AdminController::class, 'mealRequestsView'])->name('requests.meal');
    Route::get('/requests/exercise', [AdminController::class, 'exerciseRequestsView'])->name('requests.exercise');

    Route::post('/requests/coach/change-status', [AdminController::class, 'changeStatusCoachRequest'])->name('admin.coach.request.change-status');
    Route::post('/requests/premium/change-status', [AdminController::class, 'changeStatusPremiumRequest'])->name('admin.permium.request.change-status');
    Route::post('/requests/meal/change-status', [AdminController::class, 'changeStatusMealRequest'])->name('admin.meal.request.change-status');
    Route::post('/requests/exercise/change-status', [AdminController::class, 'changeStatusExerciseRequest'])->name('admin.exercise.request.change-status');

    Route::post('/payment/qr/change', [AdminController::class, 'changeQR'])->name('admin.qr.update');
});
