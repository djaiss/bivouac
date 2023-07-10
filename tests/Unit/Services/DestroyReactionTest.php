<?php

namespace Tests\Unit\Services;

use App\Models\Reaction;
use App\Models\User;
use App\Services\DestroyReaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyReactionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_reaction(): void
    {
        $user = User::factory()->create();
        $reaction = Reaction::factory()->create([
            'user_id' => $user->id,
        ]);
        $this->executeService($user, $reaction);
    }

    /** @test */
    public function it_fails_if_reaction_doesnt_belong_to_user(): void
    {
        $user = User::factory()->create();
        $reaction = Reaction::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $reaction);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyReaction)->execute($request);
    }

    private function executeService(User $user, Reaction $reaction): void
    {
        $request = [
            'user_id' => $user->id,
            'reaction_id' => $reaction->id,
        ];

        (new DestroyReaction)->execute($request);

        $this->assertDatabaseMissing('reactions', [
            'id' => $reaction->id,
        ]);
    }
}
