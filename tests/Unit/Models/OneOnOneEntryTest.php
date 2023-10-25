<?php

namespace Tests\Unit\Models;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OneOnOneEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_on_one_entry(): void
    {
        $oneOnOne = OneOnOne::factory()->create();
        $oneOnOneEntry = OneOnOneEntry::factory()->create([
            'one_on_one_id' => $oneOnOne->id,
        ]);

        $this->assertTrue($oneOnOneEntry->oneOnOne()->exists());
    }

    /** @test */
    public function it_belongs_to_a_user(): void
    {
        $user = User::factory()->create();
        $oneOnOneEntry = OneOnOneEntry::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($oneOnOneEntry->user()->exists());
    }
}
