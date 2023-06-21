<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Mail\UserInvited;
use App\Models\User;
use App\Services\InviteUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class InviteUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_invites_another_user(): void
    {
        $author = User::factory()->create([
            'permissions' => User::ROLE_ADMINISTRATOR,
        ]);
        $this->executeService($author);
    }

    /** @test */
    public function it_fails_if_email_is_already_taken(): void
    {
        $author = User::factory()->create([
            'permissions' => User::ROLE_ADMINISTRATOR,
        ]);
        User::factory()->create([
            'email' => 'admin@admin.com',
        ]);
        $this->expectException(ValidationException::class);
        $this->executeService($author);
    }

    /** @test */
    public function it_fails_if_user_is_not_organization_administrator(): void
    {
        $author = User::factory()->create([
            'permissions' => User::ROLE_USER,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($author);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new InviteUser)->execute($request);
    }

    private function executeService(User $author): void
    {
        Mail::fake();

        $request = [
            'author_id' => $author->id,
            'email' => 'admin@admin.com',
        ];

        $newUser = (new InviteUser)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $newUser->id,
            'email' => $newUser->email,
            'permissions' => User::ROLE_USER,
            'organization_id' => $author->organization_id,
            'invitation_code' => $newUser->invitation_code,
        ]);

        Mail::assertQueued(UserInvited::class, function ($mail) use ($newUser) {
            return $mail->hasTo($newUser->email);
        });
    }
}
