<?php

namespace Tests\Unit\Models;

use App\Models\Office;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\TeamType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_many_users(): void
    {
        $organization = Organization::factory()->create();
        User::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($organization->users()->exists());
    }

    /** @test */
    public function it_has_many_offices(): void
    {
        $organization = Organization::factory()->create();
        Office::factory()->create(['organization_id' => $organization->id]);

        $this->assertTrue($organization->offices()->exists());
    }

    /** @test */
    public function it_has_many_team_types(): void
    {
        $organization = Organization::factory()->create();
        TeamType::factory()->create(['organization_id' => $organization->id]);

        $this->assertTrue($organization->teamTypes()->exists());
    }

    /** @test */
    public function it_has_many_projects(): void
    {
        $organization = Organization::factory()->create();
        Project::factory()->create(['organization_id' => $organization->id]);

        $this->assertTrue($organization->projects()->exists());
    }

    /** @test */
    public function it_has_many_roles(): void
    {
        $organization = Organization::factory()->create();
        Role::factory()->create(['organization_id' => $organization->id]);

        $this->assertTrue($organization->roles()->exists());
    }
}
