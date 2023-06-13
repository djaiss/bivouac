<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\RegenerateAvatar;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RegenerateAvatarTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_generates_a_new_avatar_username(): void
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
        (new RegenerateAvatar)->execute($request);
    }

    private function executeService(User $user): void
    {
        $previousName = $user->username_avatar;

        $request = [
            'user_id' => $user->id,
        ];

        $user = (new RegenerateAvatar)->execute($request);

        $this->assertInstanceOf(
            User::class,
            $user
        );

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name_for_avatar' => $previousName,
        ]);
    }
}
