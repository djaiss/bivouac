<?php

namespace Tests\Unit\Services;

use App\Models\TaskList;
use App\Models\User;
use App\Services\CreateTaskList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateTaskListTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_task_list(): void
    {
        $user = User::factory()->create();
        $this->executeService($user);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTaskList)->execute($request);
    }

    private function executeService(User $user): void
    {
        $request = [
            'user_id' => $user->id,
            'name' => null,
        ];

        $taskList = (new CreateTaskList)->execute($request);

        $this->assertInstanceOf(
            TaskList::class,
            $taskList
        );

        $this->assertDatabaseHas('task_lists', [
            'id' => $taskList->id,
            'organization_id' => $user->organization_id,
            'name' => null,
        ]);
    }
}
