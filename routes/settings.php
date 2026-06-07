<?php

declare(strict_types=1);

use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SecurityController;
use Illuminate\Support\Facades\Route;

Route::redirect('settings', '/settings/profile')->middleware('auth');

Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('settings/security', [SecurityController::class, 'edit'])->name('security.edit');

Route::put('settings/password', [SecurityController::class, 'update'])->name('user-password.update');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('settings/appearance', 'settings/Appearance')->name('appearance.edit');
});
