<?php

namespace Tests\Unit\Services;

use App\Models\TeamType;
use App\Models\User;
use App\Services\UpdateTeamType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateTeamTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_team_type(): void
    {
        $user = User::factory()->create();

        $teamType = TeamType::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $this->executeService($user, $teamType);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateTeamType)->execute($request);
    }

    /** @test */
    public function it_fails_if_team_type_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $teamType = TeamType::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $teamType);
    }

    private function executeService(User $user, TeamType $teamType): void
    {
        $request = [
            'user_id' => $user->id,
            'team_type_id' => $teamType->id,
            'label' => 'Dunder',
        ];

        $teamType = (new UpdateTeamType)->execute($request);

        $this->assertInstanceOf(
            TeamType::class,
            $teamType
        );

        $this->assertDatabaseHas('team_types', [
            'id' => $teamType->id,
            'organization_id' => $user->organization_id,
            'label' => 'Dunder',
        ]);
    }
}
