<?php

use App\Http\Controllers\Profile\ProfileAvatarController;
use App\Http\Controllers\Profile\ProfileBirthdateController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Settings\Personalize\PersonalizeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function (): void {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/avatar', [ProfileAvatarController::class, 'update'])->name('profile.avatar.update');
    Route::put('profile/birthdate', [ProfileBirthdateController::class, 'update'])->name('profile.birthdate.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['administrator'])->prefix('settings')->group(function (): void {
        Route::get('personalize', [PersonalizeController::class, 'index'])->name('settings.personalize.index');
    });
});

require __DIR__ . '/auth.php';
