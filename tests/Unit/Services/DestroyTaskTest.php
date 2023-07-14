<?php

namespace Tests\Unit\Services;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Services\DestroyTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_task(): void
    {
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);
        $this->executeService($user, $task);
    }

    /** @test */
    public function it_fails_if_task_list_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create();
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $task);
    }

    /** @test */
    public function it_fails_if_task_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $task);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyTask)->execute($request);
    }

    private function executeService(User $user, Task $task): void
    {
        $request = [
            'user_id' => $user->id,
            'task_id' => $task->id,
        ];

        (new DestroyTask)->execute($request);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
