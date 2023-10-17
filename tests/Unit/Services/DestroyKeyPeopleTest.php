<?php

namespace Tests\Unit\Services;

use App\Models\KeyPeople;
use App\Models\Project;
use App\Models\User;
use App\Services\DestroyKeyPeople;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyKeyPeopleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_key_people(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $keyPeople = KeyPeople::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($user, $keyPeople);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyKeyPeople)->execute($request);
    }

    private function executeService(User $user, KeyPeople $keyPeople): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $request = [
            'user_id' => $user->id,
            'key_people_id' => $keyPeople->id,
        ];

        (new DestroyKeyPeople)->execute($request);

        $this->assertDatabaseMissing('key_people', [
            'id' => $keyPeople->id,
        ]);

        $this->assertDatabaseHas('projects', [
            'updated_at' => '2018-01-01 00:00:00',
        ]);
    }
}
