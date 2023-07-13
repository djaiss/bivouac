<?php

namespace Tests\Unit\Controllers\Tasks;

use App\Http\Controllers\Tasks\TaskListViewModel;
use App\Http\Controllers\Tasks\TaskViewModel;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskListViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_dto(): void
    {
        $taskList = TaskList::factory()->create([
            'name' => 'Title',
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
            'title' => 'Task',
            'is_completed' => true,
        ]);
        $array = TaskListViewModel::dto($taskList);

        $this->assertCount(4, $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('completion_rate', $array);
        $this->assertArrayHasKey('tasks', $array);

        $this->assertEquals(
            $taskList->id,
            $array['id']
        );
        $this->assertEquals(
            'Title',
            $array['name']
        );
        $this->assertEquals(
            100,
            $array['completion_rate']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $task->id,
                    'title' => 'Task',
                    'is_completed' => true,
                    'url' => [
                        'update' => env('APP_URL') . '/tasks/' . $task->id,
                        'destroy' => env('APP_URL') . '/tasks/' . $task->id,
                    ],
                ],
            ],
            $array['tasks']->toArray()
        );
    }
}
