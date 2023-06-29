<?php

namespace Tests\Unit\Models;

use App\Models\Project;
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
            'created_by_user_id' => $user->id,
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
}
