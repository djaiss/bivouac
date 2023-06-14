<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UpdateUserDateOfBirth;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateUserDateOfBirthTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_birthdate_of_the_user(): void
    {
        $user = User::factory()->create();
        $this->executeService($user);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateUserDateOfBirth)->execute($request);
    }

    private function executeService(User $user): void
    {
        $request = [
            'author_id' => $user->id,
            'user_id' => $user->id,
            'born_at' => '2022-01-01',
            'age_preferences' => 'hidden',
        ];

        $user = (new UpdateUserDateOfBirth)->execute($request);

        $this->assertInstanceOf(
            User::class,
            $user
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'born_at' => '2022-01-01 00:00:00',
            'age_preferences' => 'hidden',
        ]);
    }
}
