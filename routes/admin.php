<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\GoalController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\PlanController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\DiseaseController;

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
    Route::get('/coach/profile/{id}', [AdminController::class, 'showCoachProfile'])->name('show.coach.profile');
    Route::get('/user/profile/{id}', [AdminController::class, 'showUserProfile'])->name('show.user.profile');
    // ===============================================>>>>>>> Goals <<<<<<<<=======================================================
    Route::get('/goals', [GoalController::class, 'allGoalsView'])->name('goals.all.view');
    Route::get('/goals/edit/{id}', [GoalController::class, 'editGoalsView'])->name('goals.edit.view');
    Route::get('/goals/new', [GoalController::class, 'newGoalsView'])->name('goals.new.view');
    Route::post('/goals/edit', [GoalController::class, 'editGoals'])->name('goals.edit.update');
    Route::post('/goals/new/store', [GoalController::class, 'newGoalsStore'])->name('goals.new.store');
    Route::post('/goals/delete', [GoalController::class, 'deleteGoal'])->name('goals.delete');

    // ===============================================>>>>>>> Plans <<<<<<<<=======================================================
    Route::get('/plans', [PlanController::class, 'allPlansView'])->name('plans.all.view');
    Route::get('/plans/edit/{id}', [PlanController::class, 'editPlansView'])->name('plans.edit.view');
    Route::get('/plans/new', [PlanController::class, 'newPlansView'])->name('plans.new.view');
    Route::post('/plans/edit', [PlanController::class, 'editPlans'])->name('plans.edit.update');
    Route::post('/plans/new/store', [PlanController::class, 'newPlansStore'])->name('plans.new.store');
    Route::post('/plans/delete', [PlanController::class, 'deletePlan'])->name('plans.delete');

    // ===============================================>>>>>>> Diseases <<<<<<<<=======================================================
    Route::get('/diseases', [DiseaseController::class, 'allDiseasesView'])->name('diseases.all.view');
    Route::get('/diseases/edit/{id}', [DiseaseController::class, 'editDiseasesView'])->name('diseases.edit.view');
    Route::get('/diseases/new', [DiseaseController::class, 'newDiseasesView'])->name('diseases.new.view');
    Route::post('/diseases/edit', [DiseaseController::class, 'editDiseases'])->name('diseases.edit.update');
    Route::post('/diseases/new/store', [DiseaseController::class, 'newDiseasesStore'])->name('diseases.new.store');
    Route::post('/diseases/delete', [DiseaseController::class, 'deleteDisease'])->name('diseases.delete');

    Route::post('/requests/coach/change-status', [AdminController::class, 'changeStatusCoachRequest'])->name('admin.coach.request.change-status');
    Route::post('/requests/premium/change-status', [AdminController::class, 'changeStatusPremiumRequest'])->name('admin.permium.request.change-status');
    Route::post('/requests/meal/change-status', [AdminController::class, 'changeStatusMealRequest'])->name('admin.meal.request.change-status');
    Route::post('/requests/exercise/change-status', [AdminController::class, 'changeStatusExerciseRequest'])->name('admin.exercise.request.change-status');
    Route::post('/users/status/change', [AdminController::class, 'changeStatusUser'])->name('admin.user.status.change');

    Route::post('/payment/qr/change', [AdminController::class, 'changeQR'])->name('admin.qr.update');
});
