<?php

namespace Tests\Unit\Services;

use App\Jobs\PopulateAccount;
use App\Models\User;
use App\Services\CreateAccount;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
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
        Queue::fake();

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
            'permissions' => User::ROLE_ACCOUNT_MANAGER,
            'name_for_avatar' => 'johnny',
            'email' => 'john@email.com',
            'organization_id' => $user->organization_id,
            'invitation_code' => $user->invitation_code,
        ]);

        $this->assertDatabaseHas('organizations', [
            'id' => $user->organization_id,
            'name' => 'johnny inc',
        ]);

        Queue::assertPushed(PopulateAccount::class);
    }
}
