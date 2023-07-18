<?php

namespace Tests\Unit\Controllers\Tasks;

use App\Http\Controllers\Tasks\TaskListViewModel;
use App\Models\Message;
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
        $message = Message::factory()->create();
        $taskList = TaskList::factory()->create([
            'name' => 'Title',
            'tasklistable_id' => $message->id,
            'tasklistable_type' => Message::class,
        ]);
        $task = Task::factory()->create([
            'task_list_id' => $taskList->id,
            'title' => 'Task',
            'is_completed' => true,
        ]);
        $array = TaskListViewModel::dto($taskList);

        $this->assertCount(7, $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('completion_rate', $array);
        $this->assertArrayHasKey('tasks', $array);
        $this->assertArrayHasKey('collapsed', $array);
        $this->assertArrayHasKey('parent', $array);
        $this->assertArrayHasKey('url', $array);

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
                'store' => env('APP_URL') . '/tasks',
                'toggle' => env('APP_URL') . '/taskLists/' . $taskList->id . '/toggle',
            ],
            $array['url']
        );
        $this->assertFalse(
            $array['collapsed']
        );
        $this->assertEquals(
            [
                'id' => $message->id,
                'name' => $message->name,
                'is_project' => false,
                'url' => env('APP_URL') . '/projects/'.$message->project_id.'/messages/' . $message->id,
            ],
            $array['parent']
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
