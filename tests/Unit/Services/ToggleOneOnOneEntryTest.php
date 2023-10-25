<?php

namespace Tests\Unit\Services;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;
use App\Services\ToggleOneOnOneEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ToggleOneOnOneEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_toggles_a_one_on_one_entry(): void
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
    public function it_fails_if_entry_doesnt_belong_to_a_user(): void
    {
        $user = User::factory()->create();
        $oneOnOneEntry = OneOnOneEntry::factory()->create();

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
        (new ToggleOneOnOneEntry)->execute($request);
    }

    private function executeService(User $user, OneOnOneEntry $oneOnOneEntry): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $request = [
            'user_id' => $user->id,
            'one_on_one_entry_id' => $oneOnOneEntry->id,
        ];

        $oneOnOneEntry = (new ToggleOneOnOneEntry)->execute($request);

        $this->assertInstanceOf(
            OneOnOneEntry::class,
            $oneOnOneEntry
        );

        $this->assertDatabaseHas('one_on_one_entries', [
            'id' => $oneOnOneEntry->id,
            'checked_at' => '2018-01-01 00:00:00',
        ]);
    }
}
