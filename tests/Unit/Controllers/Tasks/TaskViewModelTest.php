<?php

namespace Tests\Unit\Controllers\Tasks;

use App\Http\Controllers\Tasks\TaskViewModel;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_for_the_show_view(): void
    {
        $task = Task::factory()->create();
        $array = TaskViewModel::show($task);

        $this->assertCount(3, $array);
        $this->assertArrayHasKey('task', $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('breadcrumb', $array);

        $this->assertEquals(
            [
                'projects' => env('APP_URL') . '/projects',
                'project' => env('APP_URL') . '/projects/' . $task->taskList->project_id,
                'tasks' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/taskLists',
            ],
            $array['breadcrumb']
        );
    }

    /** @test */
    public function it_gets_the_dto(): void
    {
        $task = Task::factory()->create([
            'title' => 'Test task',
            'description' => 'Test description',
            'is_completed' => false,
        ]);
        $assignee = User::factory()->create([
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'name_for_avatar' => 'John Doe',
        ]);
        $task->users()->attach($assignee->id);

        $reaction = Reaction::factory()->create([
            'reactionable_id' => $task->id,
            'reactionable_type' => Task::class,
        ]);

        $array = TaskViewModel::dto($task);

        $this->assertCount(9, $array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('is_completed', $array);
        $this->assertArrayHasKey('assignees', $array);
        $this->assertArrayHasKey('reactions', $array);
        $this->assertArrayHasKey('comments', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            $task->id,
            $array['id']
        );
        $this->assertEquals(
            'Test task',
            $array['title']
        );
        $this->assertEquals(
            '<p>Test description</p>
',
            $array['description']
        );
        $this->assertEquals(
            'Test description',
            $array['description_raw']
        );
        $this->assertFalse(
            $array['is_completed']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $reaction->id,
                    'emoji' => $reaction->emoji,
                    'author' => [
                        'id' => $reaction->user->id,
                        'name' => $reaction->user->name,
                        'avatar' => $reaction->user->avatar,
                        'url' => env('APP_URL') . '/users/' . $reaction->user->id,
                    ],
                    'url' => [
                        'destroy' => env('APP_URL') . '/reactions/' . $reaction->id,
                    ],
                ],
            ],
            $array['reactions']->toArray()
        );
        $this->assertEquals(
            [
                'preview' => env('APP_URL') . '/preview',
                'search_users' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id . '/search/users',
                'show' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id,
                'store' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id . '/comments',
                'store_reaction' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id . '/reactions',
                'update' => env('APP_URL') . '/tasks/' . $task->id,
                'destroy' => env('APP_URL') . '/tasks/' . $task->id,
                'assign' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id . '/assign',
                'unassign' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id . '/unassign',
            ],
            $array['url']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $assignee->id,
                    'name' => 'Michael Scott',
                    'avatar' => [
                        'type' => User::AVATAR_TYPE_SVG,
                        'content' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 231 231"><path d="M33.83,33.83a115.5,115.5,0,1,1,0,163.34,115.49,115.49,0,0,1,0-163.34Z" style="fill:#00D0D4;"/><path d="m115.5 51.75a63.75 63.75 0 0 0-10.5 126.63v14.09a115.5 115.5 0 0 0-53.729 19.027 115.5 115.5 0 0 0 128.46 0 115.5 115.5 0 0 0-53.729-19.029v-14.084a63.75 63.75 0 0 0 53.25-62.881 63.75 63.75 0 0 0-63.65-63.75 63.75 63.75 0 0 0-0.09961 0z" style="fill:#ffc016;"/><path d="m141.75 195a114.79 114.79 0 0 1 38 16.5 115.53 115.53 0 0 1-128.46 0 114.79 114.79 0 0 1 38-16.5c0 10.76 11.75 19.48 26.25 19.48s26.25-8.72 26.25-19.48z" style="fill:#d85cd7;"/><path d="m41.835 75.131c-2.8674 12.582 1.2304 27.241 6.0238 39.031 0.25861 0.63658 0.51208 1.3075 0.79989 1.9683 0.71726 1.658 2.1184 3.9751 3.0038 3.9266 0.56895-0.0312 0.71637-1.5512 1.0228-3.1562 2.1988-19.097 8.8981-27.915 15.636-38.107 2.8783-4.0645 3.8616-7.2293 1.0644-9.9325-6.3236-3.5596-14.924-2.8574-21.367-0.67406-3.2312 1.4765-5.2427 3.4773-6.1842 6.9439zm125.65-8.5679c7.65-0.70616 19.714-0.1307 21.694 8.5679 1.455 6.4083 0.26915 17.747-1.0542 24.579-1.1961 5.3203-3.8066 14.231-7.8782 19.75-0.5565 0.44544-0.96888 0.13656-1.4159-1.1606-0.90692-3.0353-1.4298-7.8372-2.2556-10.727-3.4822-12.79-8.2195-21.875-14.429-29.94-5.5782-6.8415-4.2152-9.7207 5.3393-11.069z" style="fill:none;"/><path d="m112.27 73.826c-18.585-7.5217-34.987-14.797-48.939 5.018-4.9752 7.083-3.7876 8.8056-4.9217 0.0749-1.637-12.476-4.7505-34.174 1.9259-45.194 7.6822-12.7 19.323-13.128 31.039-5.3818 10.796 7.7784 24.277 14.647 38.015 12.219 12.732-2.2576 15.835-7.7464 15.707-19.912-0.0215-2.6-0.0963-5.2106-0.2033-7.7999 13.631 3.9267 24.609 14.776 26.513 29.049 0.88804 6.6336 0.26749 12.722-1.9259 19.013-5.9702 17.108-30.119 20.896-45.74 16.841-3.9588-1.0378-7.6822-2.4181-11.47-3.9267z" style="fill:#fdff00;"/><path d="m145.39 104.7-11.52 11.2h17.26m-65.52-11.2 11.52 11.2h-17.26" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.4998px;stroke:#000;"/><path d="m136.21 147.09a21.77 21.77 0 0 1-40.13 0z" style="fill:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.4999px;stroke:#000;"/></svg>',
                    ],
                    'url' => env('APP_URL') . '/users/' . $assignee->id,
                ],
            ],
            $array['assignees']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_dto_about_the_comment(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name_for_avatar' => 'John Doe',
        ]);
        $task = Task::factory()->create();
        $comment = Comment::factory()->create([
            'commentable_id' => $task->id,
            'commentable_type' => Task::class,
            'author_id' => $user->id,
            'body' => 'Body John',
        ]);
        $array = TaskViewModel::dtoComment($task, $comment);

        $this->assertCount(7, $array);
        $this->assertEquals(
            $comment->id,
            $array['id']
        );
        $this->assertEquals(
            [
                'name' => 'John Doe',
                'avatar' => [
                    'type' => User::AVATAR_TYPE_SVG,
                    'content' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 231 231"><path d="M33.83,33.83a115.5,115.5,0,1,1,0,163.34,115.49,115.49,0,0,1,0-163.34Z" style="fill:#00D0D4;"/><path d="m115.5 51.75a63.75 63.75 0 0 0-10.5 126.63v14.09a115.5 115.5 0 0 0-53.729 19.027 115.5 115.5 0 0 0 128.46 0 115.5 115.5 0 0 0-53.729-19.029v-14.084a63.75 63.75 0 0 0 53.25-62.881 63.75 63.75 0 0 0-63.65-63.75 63.75 63.75 0 0 0-0.09961 0z" style="fill:#ffc016;"/><path d="m141.75 195a114.79 114.79 0 0 1 38 16.5 115.53 115.53 0 0 1-128.46 0 114.79 114.79 0 0 1 38-16.5c0 10.76 11.75 19.48 26.25 19.48s26.25-8.72 26.25-19.48z" style="fill:#d85cd7;"/><path d="m41.835 75.131c-2.8674 12.582 1.2304 27.241 6.0238 39.031 0.25861 0.63658 0.51208 1.3075 0.79989 1.9683 0.71726 1.658 2.1184 3.9751 3.0038 3.9266 0.56895-0.0312 0.71637-1.5512 1.0228-3.1562 2.1988-19.097 8.8981-27.915 15.636-38.107 2.8783-4.0645 3.8616-7.2293 1.0644-9.9325-6.3236-3.5596-14.924-2.8574-21.367-0.67406-3.2312 1.4765-5.2427 3.4773-6.1842 6.9439zm125.65-8.5679c7.65-0.70616 19.714-0.1307 21.694 8.5679 1.455 6.4083 0.26915 17.747-1.0542 24.579-1.1961 5.3203-3.8066 14.231-7.8782 19.75-0.5565 0.44544-0.96888 0.13656-1.4159-1.1606-0.90692-3.0353-1.4298-7.8372-2.2556-10.727-3.4822-12.79-8.2195-21.875-14.429-29.94-5.5782-6.8415-4.2152-9.7207 5.3393-11.069z" style="fill:none;"/><path d="m112.27 73.826c-18.585-7.5217-34.987-14.797-48.939 5.018-4.9752 7.083-3.7876 8.8056-4.9217 0.0749-1.637-12.476-4.7505-34.174 1.9259-45.194 7.6822-12.7 19.323-13.128 31.039-5.3818 10.796 7.7784 24.277 14.647 38.015 12.219 12.732-2.2576 15.835-7.7464 15.707-19.912-0.0215-2.6-0.0963-5.2106-0.2033-7.7999 13.631 3.9267 24.609 14.776 26.513 29.049 0.88804 6.6336 0.26749 12.722-1.9259 19.013-5.9702 17.108-30.119 20.896-45.74 16.841-3.9588-1.0378-7.6822-2.4181-11.47-3.9267z" style="fill:#fdff00;"/><path d="m145.39 104.7-11.52 11.2h17.26m-65.52-11.2 11.52 11.2h-17.26" style="fill:none;stroke-linecap:round;stroke-linejoin:round;stroke-width:5.4998px;stroke:#000;"/><path d="m136.21 147.09a21.77 21.77 0 0 1-40.13 0z" style="fill:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:3.4999px;stroke:#000;"/></svg>',
                ],
                'url' => env('APP_URL') . '/users/' . $user->id,
            ],
            $array['author']
        );
        $this->assertEquals(
            '<p>Body John</p>
',
            $array['body']
        );
        $this->assertEquals(
            'Body John',
            $array['body_raw']
        );
        $this->assertEquals(
            '2018-01-01 00:00:00',
            $array['created_at']
        );
        $this->assertEquals(
            [
                'store_reaction' => env('APP_URL') . '/comments/' . $comment->id . '/reactions',
                'update' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id . '/comments/' . $comment->id,
                'destroy' => env('APP_URL') . '/projects/' . $task->taskList->project_id . '/tasks/' . $task->id . '/comments/' . $comment->id,
            ],
            $array['url']
        );
    }
}
