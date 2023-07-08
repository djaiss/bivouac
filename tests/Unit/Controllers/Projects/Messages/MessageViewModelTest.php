<?php

namespace Tests\Unit\Controllers\Projects;

use App\Http\Controllers\Projects\Messages\MessageViewModel;
use App\Models\Message;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MessageViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $project = Project::factory()->create(['name' => 'Dunder']);
        Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $array = MessageViewModel::index($project);

        $this->assertCount(3, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('messages', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'id' => $project->id,
                'name' => 'Dunder',
                'is_public' => true,
            ],
            $array['project']
        );
        $this->assertEquals(
            [
                'create' => env('APP_URL') . '/projects/' . $project->id . '/messages/create',
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
        $array = MessageViewModel::create($project);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'project' => [
                    'name' => 'Dunder',
                ],
                'url' => [
                    'preview' => env('APP_URL') . '/projects/' . $project->id . '/messages/preview',
                    'store' => env('APP_URL') . '/projects/' . $project->id . '/messages',
                    'breadcrumb' => [
                        'projects' => env('APP_URL') . '/projects',
                        'project' => env('APP_URL') . '/projects/' . $project->id,
                        'messages' => env('APP_URL') . '/projects/' . $project->id . '/messages',
                    ],
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_data_needed_for_the_show_view(): void
    {
        $project = Project::factory()->create(['name' => 'Dunder']);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $array = MessageViewModel::show($message);

        $this->assertCount(3, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('message', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'name' => 'Dunder',
            ],
            $array['project']
        );
        $this->assertEquals(
            [
                'breadcrumb' => [
                    'projects' => env('APP_URL') . '/projects',
                    'project' => env('APP_URL') . '/projects/' . $project->id,
                    'messages' => env('APP_URL') . '/projects/' . $project->id . '/messages',
                ],
            ],
            $array['url']
        );
    }
}