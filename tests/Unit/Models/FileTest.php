<?php

namespace Tests\Unit\Models;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_organization(): void
    {
        $file = File::factory()->create([]);
        $this->assertTrue($file->organization()->exists());
    }

    /** @test */
    public function it_belongs_to_a_user(): void
    {
        $michael = User::factory()->create();
        $file = File::factory()->create([
            'uploader_id' => $michael->id,
        ]);

        $this->assertTrue($file->uploader()->exists());
    }
}
