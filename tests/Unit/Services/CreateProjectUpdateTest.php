<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\ProjectResource;
use App\Models\User;
use App\Services\CreateProjectResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateProjectUpdateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_project_update(): void
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
        (new CreateProjectResource)->execute($request);
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

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $this->expectException(NotEnoughPermissionException::class);

        $this->executeService($user, $project);
    }

    private function executeService(User $user, Project $project): void
    {
        $request = [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'name' => 'Dunder',
            'link' => 'https://laravel-livewire.com/',
        ];

        $projectResource = (new CreateProjectResource)->execute($request);

        $this->assertInstanceOf(
            ProjectResource::class,
            $projectResource
        );

        $this->assertDatabaseHas('project_resources', [
            'id' => $projectResource->id,
            'project_id' => $project->id,
            'name' => 'Dunder',
            'link' => 'https://laravel-livewire.com/',
        ]);
    }
}
