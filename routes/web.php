<?php

use App\Http\Controllers\Auth\ValidateInvitationController;
use App\Http\Controllers\Profile\ProfileAvatarController;
use App\Http\Controllers\Profile\ProfileBirthdateController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\Settings\Personalize\PersonalizeController;
use App\Http\Controllers\Settings\Personalize\PersonalizeOfficeController;
use App\Http\Controllers\Settings\Personalize\PersonalizeTeamTypeController;
use App\Http\Controllers\Settings\Personalize\PersonalizeUserController;
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

Route::get('invitation/{code}', [ValidateInvitationController::class, 'show'])->name('invitation.validate.show');
Route::put('invitation/{code}', [ValidateInvitationController::class, 'update'])->name('invitation.validate.update');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified', 'last_activity')->group(function (): void {
    Route::get('search', [SearchController::class, 'index'])->name('search.index');
    Route::post('search', [SearchController::class, 'show'])->name('search.show');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/avatar', [ProfileAvatarController::class, 'update'])->name('profile.avatar.update');
    Route::put('profile/birthdate', [ProfileBirthdateController::class, 'update'])->name('profile.birthdate.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // projects
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::middleware(['project'])->group(function (): void {
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    });

    // users
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::middleware(['administrator'])->prefix('settings')->group(function (): void {
        Route::get('personalize', [PersonalizeController::class, 'index'])->name('settings.personalize.index');

        // user management
        Route::get('personalize/users', [PersonalizeUserController::class, 'index'])->name('settings.personalize.user.index');
        Route::get('personalize/users/invite', [PersonalizeUserController::class, 'create'])->name('settings.personalize.user.create');
        Route::post('personalize/users/invite', [PersonalizeUserController::class, 'store'])->name('settings.personalize.user.store');
        Route::get('personalize/users/{user}/edit', [PersonalizeUserController::class, 'edit'])->name('settings.personalize.user.edit');
        Route::put('personalize/users/{user}', [PersonalizeUserController::class, 'update'])->name('settings.personalize.user.update');
        Route::delete('personalize/users/{user}', [PersonalizeUserController::class, 'destroy'])->name('settings.personalize.user.destroy');

        // office management
        Route::get('personalize/offices', [PersonalizeOfficeController::class, 'index'])->name('settings.personalize.office.index');
        Route::get('personalize/offices/create', [PersonalizeOfficeController::class, 'create'])->name('settings.personalize.office.create');
        Route::post('personalize/offices', [PersonalizeOfficeController::class, 'store'])->name('settings.personalize.office.store');
        Route::get('personalize/offices/{office}/edit', [PersonalizeOfficeController::class, 'edit'])->name('settings.personalize.office.edit');
        Route::put('personalize/offices/{office}', [PersonalizeOfficeController::class, 'update'])->name('settings.personalize.office.update');
        Route::delete('personalize/offices/{office}', [PersonalizeOfficeController::class, 'destroy'])->name('settings.personalize.office.destroy');

        // team type management
        Route::get('personalize/teamTypes', [PersonalizeTeamTypeController::class, 'index'])->name('settings.personalize.team_type.index');
        Route::get('personalize/teamTypes/create', [PersonalizeTeamTypeController::class, 'create'])->name('settings.personalize.team_type.create');
        Route::post('personalize/teamTypes', [PersonalizeTeamTypeController::class, 'store'])->name('settings.personalize.team_type.store');
        Route::get('personalize/teamTypes/{teamType}/edit', [PersonalizeTeamTypeController::class, 'edit'])->name('settings.personalize.team_type.edit');
        Route::put('personalize/teamTypes/{teamType}', [PersonalizeTeamTypeController::class, 'update'])->name('settings.personalize.team_type.update');
        Route::delete('personalize/teamTypes/{teamType}', [PersonalizeTeamTypeController::class, 'destroy'])->name('settings.personalize.team_type.destroy');
    });
});

require __DIR__ . '/auth.php';
