<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_organization(): void
    {
        $taskList = TaskList::factory()->create();

        $this->assertTrue($taskList->organization()->exists());
    }

    /** @test */
    public function it_has_many_tasks(): void
    {
        $taskList = TaskList::factory()->create();
        Task::factory()->create([
            'task_list_id' => $taskList->id,
        ]);

        $this->assertTrue($taskList->tasks()->exists());
    }
}
