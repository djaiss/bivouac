<?php

namespace Tests\Unit\Controllers\Tasks;

use App\Http\Controllers\Tasks\TaskViewModel;
use App\Models\Task;
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

        $this->assertCount(4, $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('is_completed', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'id' => $task->id,
                'title' => 'Test task',
                'is_completed' => false,
                'url' => [
                    'update' => env('APP_URL') . '/tasks/' . $task->id,
                    'destroy' => env('APP_URL') . '/tasks/' . $task->id,
                ],
            ],
            $array
        );
    }
}
