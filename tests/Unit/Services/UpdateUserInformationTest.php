<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UpdateUserInformation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateUserInformationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_information_of_the_user(): void
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
        (new UpdateUserInformation)->execute($request);
    }

    private function executeService(User $user): void
    {
        Event::fake();

        $request = [
            'author_id' => $user->id,
            'user_id' => $user->id,
            'first_name' => 'michael',
            'last_name' => 'scott',
            'email' => 'michael@dunder.com',
        ];

        $user = (new UpdateUserInformation)->execute($request);

        $this->assertInstanceOf(
            User::class,
            $user
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);

        Event::assertDispatched(Registered::class);
    }
}
