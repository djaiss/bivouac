<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\AddCommentToTask;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AddCommentToTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_comment(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $taskList = $project->taskLists()->create([
            'title' => 'Dunder',
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $user->projects()->attach($project->id);
        $this->executeService($user, $task);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new AddCommentToTask)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $user->projects()->attach($project->id);
        $taskList = $project->taskLists()->create([
            'title' => 'Dunder',
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $this->expectException(ModelNotFoundException::class);

        $this->executeService($user, $task);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $taskList = $project->taskLists()->create([
            'title' => 'Dunder',
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $this->expectException(NotEnoughPermissionException::class);

        $this->executeService($user, $task);
    }

    /** @test */
    public function it_fails_if_task_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $this->expectException(ModelNotFoundException::class);

        $this->executeService($user, $task);
    }

    private function executeService(User $user, Task $task): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $request = [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'body' => 'Dunder',
        ];

        $comment = (new AddCommentToTask)->execute($request);

        $this->assertInstanceOf(
            Comment::class,
            $comment
        );

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'organization_id' => $user->organization_id,
            'body' => 'Dunder',
            'commentable_id' => $task->id,
            'commentable_type' => Task::class,
        ]);

        $this->assertDatabaseHas('projects', [
            'updated_at' => '2018-01-01 00:00:00',
        ]);

        $this->assertDatabaseHas('project_user', [
            'user_id' => $user->id,
            'project_id' => $task->taskList->project_id,
        ]);
    }
}
