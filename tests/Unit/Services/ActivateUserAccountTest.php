<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\ActivateUserAccount;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ActivateUserAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_activates_the_user_account(): void
    {
        $this->executeService();
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new ActivateUserAccount)->execute($request);
    }

    private function executeService(): void
    {
        User::factory()->create([
            'invitation_code' => 'testtest',
        ]);
        $request = [
            'invitation_code' => 'testtest',
            'password' => 'testtest',
            'first_name' => 'johnny',
            'last_name' => 'depp',
        ];

        $user = (new ActivateUserAccount)->execute($request);

        $this->assertInstanceOf(
            User::class,
            $user
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'johnny',
            'last_name' => 'depp',
        ]);
    }
}
