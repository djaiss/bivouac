<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\CreateAccount;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_account(): void
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
        (new CreateAccount)->execute($request);
    }

    private function executeService(): void
    {
        $request = [
            'email' => 'john@email.com',
            'password' => 'johnny',
            'first_name' => 'johnny',
            'last_name' => 'depp',
            'organization_name' => 'johnny inc',
        ];

        $user = (new CreateAccount)->execute($request);

        $this->assertInstanceOf(
            User::class,
            $user
        );

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'johnny',
            'last_name' => 'depp',
            'email' => 'john@email.com',
            'organization_id' => $user->organization_id,
        ]);

        $this->assertDatabaseHas('organizations', [
            'id' => $user->organization_id,
            'name' => 'johnny inc',
        ]);
    }
}
