<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\DestroyCommentOfTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyCommentOfTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_comment(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $taskList = $project->taskLists()->create([
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $task->id,
            'commentable_type' => Task::class,
        ]);
        $this->executeService($user, $task, $comment);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $taskList = $project->taskLists()->create([
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $task->id,
            'commentable_type' => Task::class,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $task, $comment);
    }

    /** @test */
    public function it_fails_if_comment_doesnt_belong_to_task(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $taskList = $project->taskLists()->create([
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $task, $comment);
    }

    /** @test */
    public function it_fails_if_task_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $task->id,
            'commentable_type' => Task::class,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $task, $comment);
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
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $task->id,
            'commentable_type' => Task::class,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $task, $comment);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyCommentOfTask)->execute($request);
    }

    private function executeService(User $user, Task $task, Comment $comment): void
    {
        $request = [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'comment_id' => $comment->id,
        ];

        (new DestroyCommentOfTask)->execute($request);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
