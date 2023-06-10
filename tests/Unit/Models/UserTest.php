<?php

namespace Tests\Unit\Models;

use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_organization(): void
    {
        $organization = Organization::factory()->create();
        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->assertTrue($user->organization()->exists());
    }

    /** @test */
    public function it_gets_the_age(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $user = User::factory()->create();

        $this->assertNull($user->age);

        $user->born_at = Carbon::create(1990, 3, 14);
        $user->age_preferences = User::AGE_ONLY_MONTH_DAY;
        $user->save();

        $this->assertEquals('Mar 14', $user->age);

        $user->age_preferences = User::AGE_FULL;
        $user->save();
        $this->assertEquals('Mar 14, 1990 (27)', $user->age);
    }
}
