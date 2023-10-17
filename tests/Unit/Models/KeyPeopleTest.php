<?php

namespace Tests\Unit\Models;

use App\Models\KeyPeople;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KeyPeopleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $keyPeople = KeyPeople::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($keyPeople->user()->exists());
    }

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $project = Project::factory()->create();
        $keyPeople = KeyPeople::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->assertTrue($keyPeople->project()->exists());
    }
}
