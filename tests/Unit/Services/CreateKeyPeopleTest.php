<?php

namespace Tests\Unit\Services;

use App\Models\KeyPeople;
use App\Models\Project;
use App\Models\User;
use App\Services\CreateKeyPeople;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateKeyPeopleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_key_people(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $this->executeService($user, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new CreateKeyPeople)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $user->projects()->attach($project->id);
        $this->expectException(ModelNotFoundException::class);

        $this->executeService($user, $project);
    }

    private function executeService(User $user, Project $project): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $request = [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'role' => 'Butcher',
        ];

        $keyPeople = (new CreateKeyPeople)->execute($request);

        $this->assertInstanceOf(
            KeyPeople::class,
            $keyPeople
        );

        $this->assertDatabaseHas('key_people', [
            'id' => $keyPeople->id,
            'user_id' => $user->id,
            'project_id' => $project->id,
            'role' => 'Butcher',
        ]);

        $this->assertDatabaseHas('projects', [
            'updated_at' => '2018-01-01 00:00:00',
        ]);
    }
}
