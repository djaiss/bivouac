<?php

namespace Tests\Unit\Services;

use App\Exceptions\OneOnOneAlreadyExistsException;
use App\Models\OneOnOne;
use App\Models\User;
use App\Services\CreateOneOnOne;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateOneOnOneTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_one_on_one(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);

        $this->executeService($user, $otherUser);
    }

    /** @test */
    public function it_fails_if_the_one_on_one_already_has_an_entry(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        OneOnOne::factory()->create([
            'user_id' => $user->id,
            'other_user_id' => $otherUser->id,
        ]);

        $this->expectException(OneOnOneAlreadyExistsException::class);
        $this->executeService($user, $otherUser);
    }

    /** @test */
    public function it_fails_if_the_one_on_one_already_has_an_entry_second_test(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        OneOnOne::factory()->create([
            'user_id' => $otherUser->id,
            'other_user_id' => $user->id,
        ]);

        $this->expectException(OneOnOneAlreadyExistsException::class);
        $this->executeService($user, $otherUser);
    }

    /** @test */
    public function it_fails_if_the_two_users_are_not_in_the_same_organization(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        OneOnOne::factory()->create([
            'user_id' => $otherUser->id,
            'other_user_id' => $user->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $otherUser);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new CreateOneOnOne)->execute($request);
    }

    private function executeService(User $user, User $otherUser): void
    {
        $request = [
            'user_id' => $user->id,
            'other_user_id' => $otherUser->id,
        ];

        $oneOnOne = (new CreateOneOnOne)->execute($request);

        $this->assertInstanceOf(
            OneOnOne::class,
            $oneOnOne
        );

        $this->assertDatabaseHas('one_on_ones', [
            'id' => $oneOnOne->id,
            'user_id' => $user->id,
            'other_user_id' => $otherUser->id,
        ]);
    }
}
