<?php

namespace Tests\Unit\Services;

use App\Models\OneOnOne;
use App\Models\User;
use App\Services\DestroyOneOnOne;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyOneOnOneTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_one_on_one(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create([
            'user_id' => $user->id,
        ]);
        $this->executeService($user, $oneOnOne);
    }

    /** @test */
    public function it_destroys_a_one_on_one_second_test(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create([
            'other_user_id' => $user->id,
        ]);
        $this->executeService($user, $oneOnOne);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyOneOnOne)->execute($request);
    }

    /** @test */
    public function it_fails_if_user_doesnt_belong_to_one_on_one(): void
    {
        $user = User::factory()->create();
        $oneOnOne = OneOnOne::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $oneOnOne);
    }

    private function executeService(User $user, OneOnOne $oneOnOne): void
    {
        $request = [
            'user_id' => $user->id,
            'one_on_one_id' => $oneOnOne->id,
        ];

        (new DestroyOneOnOne)->execute($request);

        $this->assertDatabaseMissing('one_on_ones', [
            'id' => $oneOnOne->id,
        ]);
    }
}
