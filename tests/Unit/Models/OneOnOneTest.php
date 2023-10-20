<?php

namespace Tests\Unit\Models;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OneOnOneTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_user(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($oneOnOne->user()->exists());
    }

    /** @test */
    public function it_belongs_to_another_user(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create([
            'other_user_id' => $user->id,
        ]);

        $this->assertTrue($oneOnOne->otherUser()->exists());
    }

    /** @test */
    public function it_has_many_one_on_one_entries(): void
    {
        $oneOnOne = OneOnOne::factory()->create();
        OneOnOneEntry::factory()->create(['one_on_one_id' => $oneOnOne->id]);

        $this->assertTrue($oneOnOne->oneOnOneEntries()->exists());
    }
}
