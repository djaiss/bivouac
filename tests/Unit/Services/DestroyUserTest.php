<?php

namespace Tests\Unit\Services;

use App\Exceptions\CantDeleteHimselfException;
use App\Exceptions\NotEnoughPermissionException;
use App\Models\User;
use App\Services\DestroyUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_an_user(): void
    {
        $author = User::factory()->create([
            'permissions' => User::ROLE_ADMINISTRATOR,
        ]);
        $user = User::factory()->create([
            'organization_id' => $author->organization_id,
        ]);
        $this->executeService($author, $user);
    }

    /** @test */
    public function it_fails_if_user_is_not_organization_administrator(): void
    {
        $author = User::factory()->create([
            'permissions' => User::ROLE_USER,
        ]);
        $user = User::factory()->create([
            'organization_id' => $author->organization_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($author, $user);
    }

    /** @test */
    public function it_fails_if_user_is_not_in_author_organization(): void
    {
        $author = User::factory()->create([
            'permissions' => User::ROLE_ADMINISTRATOR,
        ]);
        $user = User::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($author, $user);
    }

    /** @test */
    public function it_fails_if_user_is_the_author(): void
    {
        $author = User::factory()->create([
            'permissions' => User::ROLE_ADMINISTRATOR,
        ]);

        $this->expectException(CantDeleteHimselfException::class);
        $this->executeService($author, $author);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyUser)->execute($request);
    }

    private function executeService(User $author, User $user): void
    {
        $request = [
            'author_id' => $author->id,
            'user_id' => $user->id,
        ];

        (new DestroyUser)->execute($request);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
