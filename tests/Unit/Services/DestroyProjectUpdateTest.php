<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;
use App\Services\DestroyProjectUpdate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyProjectUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_project_update(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $projectUpdate = ProjectUpdate::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($user, $project, $projectUpdate);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $projectUpdate = ProjectUpdate::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $project, $projectUpdate);
    }

    /** @test */
    public function it_fails_if_project_update_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $projectUpdate = ProjectUpdate::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $project, $projectUpdate);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $projectUpdate = ProjectUpdate::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $project, $projectUpdate);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyProjectUpdate)->execute($request);
    }

    private function executeService(User $user, Project $project, ProjectUpdate $projectUpdate): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $request = [
            'user_id' => $user->id,
            'project_update_id' => $projectUpdate->id,
        ];

        (new DestroyProjectUpdate)->execute($request);

        $this->assertDatabaseMissing('project_updates', [
            'id' => $projectUpdate->id,
        ]);

        $this->assertDatabaseHas('projects', [
            'updated_at' => '2018-01-01 00:00:00',
        ]);
    }
}
