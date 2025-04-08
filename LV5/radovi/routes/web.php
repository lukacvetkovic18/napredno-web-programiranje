<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/assign-role/{userId}', [RoleController::class, 'assignRole'])->name('admin.assign.role');
    });
});

Route::middleware(['auth', 'role:nastavnik'])->group(function () {
    Route::get('/tasks/my', [TaskController::class, 'myTasks'])->name('tasks.my');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{taskId}/applications', [TaskController::class, 'showApplications'])->name('tasks.applications');
    Route::post('/applications/{applicationId}/accept', [TaskController::class, 'acceptStudent'])->name('applications.accept');
});

Route::get('/lang/{locale}', function ($locale) {
    $validLocales = ['en', 'hr'];

    if (!in_array($locale, $validLocales)) {
        abort(400, 'Invalid locale');
    }
    session(['locale' => $locale]);

    return redirect()->back() ?? redirect('/');
})->name('lang.switch');


Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'indexForStudent'])->name('tasks.index');
    Route::post('/tasks/apply/{taskId}', [TaskController::class, 'apply'])->name('tasks.apply');
});