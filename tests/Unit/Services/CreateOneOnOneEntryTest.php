<?php

namespace Tests\Unit\Services;

use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;
use App\Services\CreateOneOnOneEntry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateOneOnOneEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_one_on_one_entry(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->executeService($user, $oneOnOne);
    }

    /** @test */
    public function it_fails_if_the_one_on_one_is_not_linked_to_user(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $oneOnOne);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new CreateOneOnOneEntry)->execute($request);
    }

    private function executeService(User $user, OneOnOne $oneOnOne): void
    {
        $request = [
            'user_id' => $user->id,
            'one_on_one_id' => $oneOnOne->id,
            'body' => 'Ross',
        ];

        $oneOnOneEntry = (new CreateOneOnOneEntry)->execute($request);

        $this->assertInstanceOf(
            OneOnOneEntry::class,
            $oneOnOneEntry
        );

        $this->assertDatabaseHas('one_on_one_entries', [
            'id' => $oneOnOneEntry->id,
            'one_on_one_id' => $oneOnOne->id,
            'body' => 'Ross',
        ]);
    }
}
