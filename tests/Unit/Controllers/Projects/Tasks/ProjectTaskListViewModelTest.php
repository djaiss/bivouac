<?php

namespace Tests\Unit\Controllers\Projects\Tasks;

use App\Http\Controllers\Projects\Tasks\ProjectTaskListViewModel;
use App\Models\Message;
use App\Models\Project;
use App\Models\TaskList;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectTaskListViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $project = Project::factory()->create(['name' => 'Dunder']);
        Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $array = ProjectTaskListViewModel::index($project);

        $this->assertCount(3, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('task_lists', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'id' => $project->id,
                'name' => 'Dunder',
                'short_description' => $project->short_description,
                'description' => $project->description,
                'is_public' => true,
            ],
            $array['project']
        );
        $this->assertEquals(
            [
                'create' => env('APP_URL') . '/projects/' . $project->id . '/taskLists/create',
                'breadcrumb' => [
                    'home' => env('APP_URL') . '/profile',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_data_needed_for_the_create_view(): void
    {
        $project = Project::factory()->create(['name' => 'Dunder']);
        $array = ProjectTaskListViewModel::create($project);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'project' => [
                    'name' => 'Dunder',
                ],
                'url' => [
                    'store' => env('APP_URL') . '/projects/' . $project->id . '/taskLists',
                    'breadcrumb' => [
                        'projects' => env('APP_URL') . '/projects',
                        'project' => env('APP_URL') . '/projects/' . $project->id,
                        'tasks' => env('APP_URL') . '/projects/' . $project->id . '/taskLists',
                    ],
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_data_needed_for_the_edit_view(): void
    {
        $project = Project::factory()->create(['name' => 'Dunder']);
        $taskList = TaskList::factory()->create([
            'project_id' => $project->id,
            'name' => 'Dunder',
        ]);
        $array = ProjectTaskListViewModel::edit($project, $taskList);

        $this->assertCount(3, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('task_list', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'id' => $taskList->id,
                'name' => 'Dunder',
            ],
            $array['task_list']
        );
        $this->assertEquals(
            [
                'name' => 'Dunder',
            ],
            $array['project']
        );
        $this->assertEquals(
            [
                'update' => env('APP_URL') . '/projects/' . $project->id . '/taskLists/' . $taskList->id,
                'destroy' => env('APP_URL') . '/projects/' . $project->id . '/taskLists/' . $taskList->id,
                'breadcrumb' => [
                    'projects' => env('APP_URL') . '/projects',
                    'project' => env('APP_URL') . '/projects/' . $project->id,
                    'tasks' => env('APP_URL') . '/projects/' . $project->id . '/taskLists',
                ],
            ],
            $array['url']
        );
    }
}
