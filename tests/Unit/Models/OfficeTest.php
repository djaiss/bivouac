<?php

namespace Tests\Unit\Models;

use App\Models\Office;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OfficeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_organization(): void
    {
        $office = Office::factory()->create();

        $this->assertTrue($office->organization()->exists());
    }
}
