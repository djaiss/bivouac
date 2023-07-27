<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Services\RemoveUserFromTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RemoveUserFromTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_unassigns_a_user_from_a_task(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $user->projects()->attach($project->id);
        $assignee->tasks()->attach($task->id);

        $this->executeService($user, $assignee, $task);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new RemoveUserFromTask)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create();
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $user->projects()->attach($project->id);
        $assignee->tasks()->attach($task->id);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $assignee, $task);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $assignee->projects()->attach($project->id);
        $assignee->tasks()->attach($task->id);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $assignee, $task);
    }

    /** @test */
    public function it_fails_if_assignee_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $user->projects()->attach($project->id);
        $assignee->tasks()->attach($task->id);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $assignee, $task);
    }

    /** @test */
    public function it_fails_if_assignee_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $user->projects()->attach($project->id);
        $assignee->tasks()->attach($task->id);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $assignee, $task);
    }

    /** @test */
    public function it_fails_if_task_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $assignee = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $taskList = TaskList::factory()->create();
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $user->projects()->attach($project->id);
        $assignee->tasks()->attach($task->id);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $assignee, $task);
    }

    private function executeService(User $user, User $assignee, Task $task): void
    {
        $this->assertDatabaseHas('task_user', [
            'user_id' => $assignee->id,
            'task_id' => $task->id,
        ]);

        $request = [
            'user_id' => $user->id,
            'assignee_id' => $assignee->id,
            'task_id' => $task->id,
        ];

        (new RemoveUserFromTask)->execute($request);

        $this->assertDatabaseMissing('task_user', [
            'user_id' => $assignee->id,
            'task_id' => $task->id,
        ]);
    }
}
