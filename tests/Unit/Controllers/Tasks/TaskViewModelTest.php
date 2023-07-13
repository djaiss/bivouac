<?php

namespace Tests\Unit\Controllers\Tasks;

use App\Http\Controllers\Tasks\TaskViewModel;
use App\Models\Reaction;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_dto(): void
    {
        $task = Task::factory()->create([
            'title' => 'Test task',
        ]);
        $array = TaskViewModel::dto($task);

        $this->assertCount(3, $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('task', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'id' => $task->id,
                'task' => 'Test task',
                'url' => [
                    'destroy' => env('APP_URL') . '/tasks/' . $task->id,
                ],
            ],
            $array
        );
    }
}
