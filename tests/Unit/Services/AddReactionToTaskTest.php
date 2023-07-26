<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\Reaction;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Services\AddReactionToTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AddReactionToTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_reaction(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $user->projects()->attach($project->id);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $this->executeService($user, $task);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new AddReactionToTask)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $user->projects()->attach($project->id);
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
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
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
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $taskList = TaskList::factory()->create();
        $user->projects()->attach($project->id);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $task);
    }

    private function executeService(User $user, Task $task): void
    {
        $request = [
            'user_id' => $user->id,
            'task_id' => $task->id,
            'emoji' => '🥳',
        ];

        $reaction = (new AddReactionToTask)->execute($request);

        $this->assertInstanceOf(
            Reaction::class,
            $reaction
        );

        $this->assertDatabaseHas('reactions', [
            'id' => $reaction->id,
            'organization_id' => $user->organization_id,
            'emoji' => '🥳',
            'reactionable_id' => $task->id,
            'reactionable_type' => Task::class,
        ]);
    }
}
