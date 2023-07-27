<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Task;
use App\Models\User;
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

    /** @test */
    public function it_belongs_to_many_users(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $task->users()->attach($user);

        $this->assertTrue($task->users()->exists());
    }

    /** @test */
    public function it_has_many_reactions(): void
    {
        $task = Task::factory()->create();
        Reaction::factory()->create([
            'reactionable_id' => $task->id,
            'reactionable_type' => Task::class,
        ]);

        $this->assertTrue($task->reactions()->exists());
    }

    /** @test */
    public function it_has_many_comments(): void
    {
        $task = Task::factory()->create();
        Comment::factory()->create([
            'commentable_id' => $task->id,
            'commentable_type' => Task::class,
        ]);

        $this->assertTrue($task->comments()->exists());
    }
}
