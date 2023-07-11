<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_task_list(): void
    {
        $task = Task::factory()->create();

        $this->assertTrue($task->taskList()->exists());
    }
}
