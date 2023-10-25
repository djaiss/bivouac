<?php

namespace Tests\Unit\Models;

use App\Models\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_organization(): void
    {
        $file = File::factory()->create();

        $this->assertTrue($file->organization()->exists());
    }

    /** @test */
    public function it_belongs_to_one_project(): void
    {
        $file = File::factory()->create();

        $this->assertTrue($file->project()->exists());
    }
}
