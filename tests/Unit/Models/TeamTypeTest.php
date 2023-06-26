<?php

namespace Tests\Unit\Models;

use App\Models\TeamType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TeamTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_organization(): void
    {
        $teamType = TeamType::factory()->create();
        $this->assertTrue($teamType->organization()->exists());
    }
}
