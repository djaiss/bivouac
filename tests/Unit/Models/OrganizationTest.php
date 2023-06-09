<?php

namespace Tests\Unit\Models;

use App\Models\Organization;
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
}