<?php

namespace Tests\Unit\Models;

use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_project(): void
    {
        $projectUpdate = ProjectUpdate::factory()->create();

        $this->assertTrue($projectUpdate->project()->exists());
    }

    /** @test */
    public function it_belongs_to_one_creator(): void
    {
        $user = User::factory()->create();
        $projectUpdate = Project::factory()->create([
            'author_id' => $user->id,
        ]);

        $this->assertTrue($projectUpdate->creator()->exists());
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
