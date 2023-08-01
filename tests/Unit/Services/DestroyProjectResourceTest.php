<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Models\User;
use App\Services\DestroyProjectResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyProjectResourceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_project_resource(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $projectResource = ProjectResource::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($user, $project, $projectResource);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $projectResource = ProjectResource::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $project, $projectResource);
    }

    /** @test */
    public function it_fails_if_project_resource_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $projectResource = ProjectResource::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $project, $projectResource);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $projectResource = ProjectResource::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $project, $projectResource);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyProjectResource)->execute($request);
    }

    private function executeService(User $user, Project $project, ProjectResource $projectResource): void
    {
        $request = [
            'user_id' => $user->id,
            'project_resource_id' => $projectResource->id,
        ];

        (new DestroyProjectResource)->execute($request);

        $this->assertDatabaseMissing('project_resources', [
            'id' => $projectResource->id,
        ]);
    }
}
