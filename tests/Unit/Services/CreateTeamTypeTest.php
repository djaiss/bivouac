<?php

namespace Tests\Unit\Services;

use App\Models\TeamType;
use App\Models\User;
use App\Services\CreateTeamType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateTeamTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_team_type(): void
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
        (new CreateTeamType)->execute($request);
    }

    private function executeService(): void
    {
        $user = User::factory()->create();
        TeamType::factory()->create([
            'organization_id' => $user->organization_id,
            'position' => 2,
        ]);
        $request = [
            'user_id' => $user->id,
            'label' => 'Dunder',
        ];

        $teamType = (new CreateTeamType)->execute($request);

        $this->assertInstanceOf(
            TeamType::class,
            $teamType
        );

        $this->assertDatabaseHas('team_types', [
            'id' => $teamType->id,
            'organization_id' => $user->organization_id,
            'label' => 'Dunder',
            'position' => 3,
        ]);
    }
}
