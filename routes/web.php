<?php

use App\Http\Controllers\Auth\ValidateInvitationController;
use App\Http\Controllers\Files\FileController;
use App\Http\Controllers\Files\FileDownloadController;
use App\Http\Controllers\OneOnOnes\OneOnOneController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\Profile\ProfileAvatarController;
use App\Http\Controllers\Profile\ProfileBirthdateController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Projects\CommentReactionController;
use App\Http\Controllers\Projects\Files\MessageFileController;
use App\Http\Controllers\Projects\Files\TaskFileController;
use App\Http\Controllers\Projects\Members\MemberController;
use App\Http\Controllers\Projects\Members\MemberUserController;
use App\Http\Controllers\Projects\Messages\MessageCommentController;
use App\Http\Controllers\Projects\Messages\MessageController;
use App\Http\Controllers\Projects\Messages\MessageReactionController;
use App\Http\Controllers\Projects\ProjectController;
use App\Http\Controllers\Projects\Summary\ProjectKeyPeopleController;
use App\Http\Controllers\Projects\Summary\ProjectResourceController;
use App\Http\Controllers\Projects\Summary\ProjectUpdateController;
use App\Http\Controllers\Projects\Tasks\ProjectAssignTaskController;
use App\Http\Controllers\Projects\Tasks\ProjectTaskListController;
use App\Http\Controllers\Projects\Tasks\TaskCommentController;
use App\Http\Controllers\Projects\Tasks\TaskReactionController;
use App\Http\Controllers\Reactions\ReactionController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\Settings\Personalize\PersonalizeController;
use App\Http\Controllers\Settings\Personalize\PersonalizeOfficeController;
use App\Http\Controllers\Settings\Personalize\PersonalizeTeamTypeController;
use App\Http\Controllers\Settings\Personalize\PersonalizeUpgradeController;
use App\Http\Controllers\Settings\Personalize\PersonalizeUserController;
use App\Http\Controllers\Tasks\TaskController;
use App\Http\Controllers\Tasks\TaskListController;
use App\Http\Controllers\Tasks\TaskSearchUserController;
use App\Http\Controllers\Users\UserSearchController;
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
    // universal search
    Route::get('search', [SearchController::class, 'index'])->name('search.index');
    Route::post('search', [SearchController::class, 'show'])->name('search.show');

    // profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/avatar', [ProfileAvatarController::class, 'update'])->name('profile.avatar.update');
    Route::put('profile/birthdate', [ProfileBirthdateController::class, 'update'])->name('profile.birthdate.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // preview markdown
    Route::post('preview', [PreviewController::class, 'store'])->name('preview.store');

    // delete reaction
    Route::delete('reactions/{reaction}', [ReactionController::class, 'destroy'])->name('reactions.destroy');

    // download file
    Route::middleware(['media'])->group(function (): void {
        Route::get('media/{media}/download', [FileDownloadController::class, 'show'])->name('files.download.show');
        Route::delete('media/{media}', [FileController::class, 'destroy'])->name('files.destroy');
    });

    // tasks
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // search users
    Route::post('search/users', [UserSearchController::class, 'index'])->name('user.search.index');

    // reaction to comments
    Route::post('comments/{comment}/reactions', [CommentReactionController::class, 'store'])->name('comments.reactions.store');

    // task lists and tasks
    Route::middleware(['taskList'])->group(function (): void {
        Route::put('taskLists/{taskList}/toggle', [TaskListController::class, 'toggle'])->name('task_lists.toggle');
    });

    // one on ones
    Route::get('oneonones', [OneOnOneController::class, 'index'])->name('oneonones.index');
    Route::get('oneonones/create', [OneOnOneController::class, 'create'])->name('oneonones.create');
    Route::post('oneonones', [OneOnOneController::class, 'store'])->name('oneonones.store');
    Route::middleware(['oneOnOne'])->group(function (): void {
        Route::get('oneonones/{oneOnOne}', [OneOnOneController::class, 'show'])->name('oneonones.show');
    });

    // projects
    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::middleware(['project'])->group(function (): void {
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

        // messages
        Route::get('projects/{project}/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('projects/{project}/messages/create', [MessageController::class, 'create'])->name('messages.create');
        Route::post('projects/{project}/messages', [MessageController::class, 'store'])->name('messages.store');

        Route::middleware(['message'])->group(function (): void {
            Route::get('projects/{project}/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
            Route::get('projects/{project}/messages/{message}/edit', [MessageController::class, 'edit'])->name('messages.edit');
            Route::put('projects/{project}/messages/{message}/edit', [MessageController::class, 'update'])->name('messages.update');
            Route::delete('projects/{project}/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

            // files
            Route::get('projects/{project}/messages/{message}/media', [MessageFileController::class, 'index'])->name('messages.files.index');
            Route::post('projects/{project}/messages/{message}/upload', [MessageFileController::class, 'store'])->name('messages.files.store');

            // add reaction
            Route::post('projects/{project}/messages/{message}/reactions', [MessageReactionController::class, 'store'])->name('messages.reactions.store');

            // comments
            Route::post('projects/{project}/messages/{message}/comments', [MessageCommentController::class, 'store'])->name('messages.comments.store');
            Route::put('projects/{project}/messages/{message}/comments/{comment}', [MessageCommentController::class, 'update'])->name('messages.comments.update');
            Route::delete('projects/{project}/messages/{message}/comments/{comment}', [MessageCommentController::class, 'destroy'])->name('messages.comments.destroy');
        });

        // project updates
        Route::post('projects/{project}/updates', [ProjectUpdateController::class, 'store'])->name('project_updates.store');
        Route::put('projects/{project}/updates/{update}', [ProjectUpdateController::class, 'update'])->name('project_updates.update');
        Route::delete('projects/{project}/updates/{update}', [ProjectUpdateController::class, 'destroy'])->name('project_updates.destroy');

        // key people
        Route::post('projects/{project}/keyPeople', [ProjectKeyPeopleController::class, 'store'])->name('key_people.store');
        Route::delete('projects/{project}/keyPeople/{people}', [ProjectKeyPeopleController::class, 'destroy'])->name('key_people.destroy');

        // tasklists
        Route::get('projects/{project}/taskLists', [ProjectTaskListController::class, 'index'])->name('tasks.index');
        Route::get('projects/{project}/taskLists/create', [ProjectTaskListController::class, 'create'])->name('task_lists.create');
        Route::post('projects/{project}/taskLists', [ProjectTaskListController::class, 'store'])->name('task_lists.store');
        Route::get('projects/{project}/taskLists/{taskList}/edit', [ProjectTaskListController::class, 'edit'])->name('task_lists.edit');
        Route::put('projects/{project}/taskLists/{taskList}', [ProjectTaskListController::class, 'update'])->name('task_lists.update');
        Route::delete('projects/{project}/taskLists/{taskList}', [ProjectTaskListController::class, 'destroy'])->name('task_lists.destroy');

        // assign user to task
        Route::post('projects/{project}/tasks/{task}/assign', [ProjectAssignTaskController::class, 'store'])->name('tasks.assign.store');
        Route::post('projects/{project}/tasks/{task}/unassign', [ProjectAssignTaskController::class, 'destroy'])->name('tasks.assign.destroy');

        // tasks
        Route::get('projects/{project}/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::get('projects/{project}/tasks/{task}/media', [TaskFileController::class, 'index'])->name('tasks.files.index');
        Route::post('projects/{project}/tasks/{task}/upload', [TaskFileController::class, 'store'])->name('tasks.files.store');

        // members
        Route::get('projects/{project}/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('projects/{project}/users', [MemberUserController::class, 'index'])->name('members.user.index');
        Route::post('projects/{project}/members/{member}', [MemberUserController::class, 'store'])->name('members.user.store');
        Route::delete('projects/{project}/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

        // add reaction and message to tasks
        Route::post('projects/{project}/tasks/{task}/reactions', [TaskReactionController::class, 'store'])->name('tasks.reactions.store');
        Route::post('projects/{project}/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('tasks.comments.store');
        Route::put('projects/{project}/tasks/{task}/comments/{comment}', [TaskCommentController::class, 'update'])->name('tasks.comments.update');
        Route::delete('projects/{project}/tasks/{task}/comments/{comment}', [TaskCommentController::class, 'destroy'])->name('tasks.comments.destroy');

        // search a specific user to assign a task to
        Route::post('projects/{project}/tasks/{task}/search/users', [TaskSearchUserController::class, 'index'])->name('tasks.search.user.index');

        // project resources
        Route::post('projects/{project}/resources', [ProjectResourceController::class, 'store'])->name('projects.resources.store');
        Route::put('projects/{project}/resources/{projectResource}', [ProjectResourceController::class, 'update'])->name('projects.resources.update');
        Route::delete('projects/{project}/resources/{projectResource}', [ProjectResourceController::class, 'destroy'])->name('projects.resources.destroy');
    });

    // users
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

    Route::middleware(['administrator'])->group(function (): void {
        Route::get('settings', [PersonalizeController::class, 'index'])->name('settings.index');

        // user management
        Route::get('settings/users', [PersonalizeUserController::class, 'index'])->name('settings.user.index');
        Route::get('settings/users/invite', [PersonalizeUserController::class, 'create'])->name('settings.user.create');
        Route::post('settings/users/invite', [PersonalizeUserController::class, 'store'])->name('settings.user.store');
        Route::get('settings/users/{user}/edit', [PersonalizeUserController::class, 'edit'])->name('settings.user.edit');
        Route::put('settings/users/{user}', [PersonalizeUserController::class, 'update'])->name('settings.user.update');
        Route::delete('settings/users/{user}', [PersonalizeUserController::class, 'destroy'])->name('settings.user.destroy');

        // office management
        Route::get('settings/offices', [PersonalizeOfficeController::class, 'index'])->name('settings.office.index');
        Route::get('settings/offices/create', [PersonalizeOfficeController::class, 'create'])->name('settings.office.create');
        Route::post('settings/offices', [PersonalizeOfficeController::class, 'store'])->name('settings.office.store');
        Route::get('settings/offices/{office}/edit', [PersonalizeOfficeController::class, 'edit'])->name('settings.office.edit');
        Route::put('settings/offices/{office}', [PersonalizeOfficeController::class, 'update'])->name('settings.office.update');
        Route::delete('settings/offices/{office}', [PersonalizeOfficeController::class, 'destroy'])->name('settings.office.destroy');

        // team type management
        Route::get('settings/teamTypes', [PersonalizeTeamTypeController::class, 'index'])->name('settings.team_type.index');
        Route::get('settings/teamTypes/create', [PersonalizeTeamTypeController::class, 'create'])->name('settings.team_type.create');
        Route::post('settings/teamTypes', [PersonalizeTeamTypeController::class, 'store'])->name('settings.team_type.store');
        Route::get('settings/teamTypes/{teamType}/edit', [PersonalizeTeamTypeController::class, 'edit'])->name('settings.team_type.edit');
        Route::put('settings/teamTypes/{teamType}', [PersonalizeTeamTypeController::class, 'update'])->name('settings.team_type.update');
        Route::delete('settings/teamTypes/{teamType}', [PersonalizeTeamTypeController::class, 'destroy'])->name('settings.team_type.destroy');

        Route::middleware(['account_manager'])->group(function (): void {
            Route::get('settings/upgrade', [PersonalizeUpgradeController::class, 'index'])->name('settings.upgrade.index');
            Route::put('settings/upgrade', [PersonalizeUpgradeController::class, 'update'])->name('settings.upgrade.update');
        });
    });
});

require __DIR__ . '/auth.php';
