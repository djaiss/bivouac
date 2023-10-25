<?php

namespace Tests\Unit\Services;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;
use App\Services\UpdateOneOnOneEntry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateOneOnOneEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_one_on_one_entry(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create([
            'user_id' => $user->id,
        ]);
        $oneOnOneEntry = OneOnOneEntry::factory()->create([
            'one_on_one_id' => $oneOnOne->id,
        ]);

        $this->executeService($user, $oneOnOneEntry);
    }

    /** @test */
    public function it_fails_if_the_one_on_one_entry_does_not_belong_to_user(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create();
        $oneOnOneEntry = OneOnOneEntry::factory()->create([
            'one_on_one_id' => $oneOnOne->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $oneOnOneEntry);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateOneOnOneEntry)->execute($request);
    }

    private function executeService(User $user, OneOnOneEntry $oneOnOneEntry): void
    {
        $request = [
            'user_id' => $user->id,
            'one_on_one_entry_id' => $oneOnOneEntry->id,
            'body' => 'Ross',
        ];

        $oneOnOneEntry = (new UpdateOneOnOneEntry)->execute($request);

        $this->assertInstanceOf(
            OneOnOneEntry::class,
            $oneOnOneEntry
        );

        $this->assertDatabaseHas('one_on_one_entries', [
            'id' => $oneOnOneEntry->id,
            'body' => 'Ross',
        ]);
    }
}
