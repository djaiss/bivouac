<?php

namespace Tests\Unit\Services;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use App\Services\CreateTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_task(): void
    {
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $this->executeService($user, $taskList);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTask)->execute($request);
    }

    /** @test */
    public function it_fails_if_task_list_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $taskList = TaskList::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $taskList);
    }

    private function executeService(User $user, TaskList $taskList): void
    {
        $request = [
            'user_id' => $user->id,
            'task_list_id' => $taskList->id,
            'title' => 'Super name',
        ];

        $task = (new CreateTask)->execute($request);

        $this->assertInstanceOf(
            Task::class,
            $task
        );

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'task_list_id' => $taskList->id,
            'title' => 'Super name',
        ]);
    }
}
