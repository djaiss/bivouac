<?php

namespace Tests\Unit\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\CreateRole;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateRoleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_role(): void
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
        (new CreateRole)->execute($request);
    }

    private function executeService(): void
    {
        $user = User::factory()->create();
        Role::factory()->create([
            'organization_id' => $user->organization_id,
            'position' => 2,
        ]);
        $request = [
            'user_id' => $user->id,
            'label' => 'Dunder',
        ];

        $role = (new CreateRole)->execute($request);

        $this->assertInstanceOf(
            Role::class,
            $role
        );

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'organization_id' => $user->organization_id,
            'label' => 'Dunder',
            'position' => 3,
        ]);
    }
}
