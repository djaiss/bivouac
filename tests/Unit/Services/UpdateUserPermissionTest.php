<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UpdateUserPermission;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateUserPermissionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_permission_of_the_user(): void
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
        (new UpdateUserPermission)->execute($request);
    }

    private function executeService(User $user): void
    {
        $request = [
            'author_id' => $user->id,
            'user_id' => $user->id,
            'permissions' => 'michael',
        ];

        $user = (new UpdateUserPermission)->execute($request);

        $this->assertInstanceOf(
            User::class,
            $user
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'permissions' => 'michael',
        ]);
    }
}
