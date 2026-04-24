<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin Routes
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Category Routes
    Route::get('/kategori', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('category.index');
    Route::post('/kategori', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('category.store');
    Route::put('/kategori/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('category.update');
    Route::delete('/kategori/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('category.destroy');

    // Report Routes
    Route::get('/laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('report.index');
    Route::get('/laporan/buat', [\App\Http\Controllers\Admin\ReportController::class, 'create'])->name('report.create');
    Route::post('/laporan', [\App\Http\Controllers\Admin\ReportController::class, 'store'])->name('report.store');
    Route::put('/laporan/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'update'])->name('report.update');
    Route::delete('/laporan/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'destroy'])->name('report.destroy');
});

require __DIR__.'/auth.php';
