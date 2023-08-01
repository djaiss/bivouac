<?php

namespace Tests\Unit\Models;

use App\Models\Message;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_organization(): void
    {
        $project = Project::factory()->create();

        $this->assertTrue($project->organization()->exists());
    }

    /** @test */
    public function it_belongs_to_one_creator(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'author_id' => $user->id,
        ]);

        $this->assertTrue($project->creator()->exists());
    }

    /** @test */
    public function it_belongs_to_many_users(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->users()->attach($user);

        $this->assertTrue($project->users()->exists());
    }

    /** @test */
    public function it_has_many_messages(): void
    {
        $project = Project::factory()->create();
        Message::factory()->create(['project_id' => $project->id]);

        $this->assertTrue($project->messages()->exists());
    }

    /** @test */
    public function it_has_many_task_lists(): void
    {
        $project = Project::factory()->create();
        TaskList::factory()->create(['project_id' => $project->id]);

        $this->assertTrue($project->taskLists()->exists());
    }

    /** @test */
    public function it_has_many_project_resources(): void
    {
        $project = Project::factory()->create();
        ProjectResource::factory()->create(['project_id' => $project->id]);

        $this->assertTrue($project->projectResources()->exists());
    }

    /** @test */
    public function it_gets_the_author(): void
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $project = Project::factory()->create([
            'author_id' => null,
            'author_name' => 'Henri Troyat',
        ]);

        $this->assertEquals(
            'Henri Troyat',
            $project->author
        );

        $project->author_id = $user->id;
        $project->save();

        $this->assertEquals(
            'John Doe',
            $project->author
        );
    }
}
