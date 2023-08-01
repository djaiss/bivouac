<?php

namespace Tests\Unit\Models;

use App\Models\ProjectResource;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProjectResourceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_project(): void
    {
        $projectResource = ProjectResource::factory()->create();

        $this->assertTrue($projectResource->project()->exists());
    }
}
