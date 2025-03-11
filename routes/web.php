<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Profile routes - new implementation
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::patch('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/user/password', [PasswordController::class, 'update'])->name('user.password.update');

    // Resource routes
    Route::resource('categories', CategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('devices', DeviceController::class)->only(['store', 'update', 'destroy']);
    Route::resource('repairs', RepairController::class);
    Route::resource('inventory', InventoryController::class);

    Route::get('/repairs/{repair}/receipt', [RepairController::class, 'receipt'])->name('repairs.receipt');

    // Report routes
    Route::get('/reports', [ReportController::class, 'generate'])->name('reports.generate');

    // Add this with your other routes
    Route::get('/api/customers/{customer}/devices', [CustomerController::class, 'getDevices'])
        ->name('customers.devices');
        
    // Add device to customer
    Route::post('/customers/{customer}/devices', [CustomerController::class, 'addDevice'])
        ->name('customers.devices.store')
        ->middleware('auth');
        
    // API route for services
    Route::get('/api/services', [ServiceController::class, 'index'])
        ->name('api.services')
        ->middleware('auth');
});

require __DIR__.'/auth.php';

// pag blade ra dayon ahhahaa=== sajton diay lipat kos icon ahahha