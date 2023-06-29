<?php

namespace Tests\Unit\Services;

use App\Models\Project;
use App\Models\User;
use App\Services\UpdateProject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_project(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $this->executeService($user, $project);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateProject)->execute($request);
    }

    private function executeService(User $user, Project $project): void
    {
        $request = [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'name' => 'Dunder',
            'description' => 'this is a description',
            'is_public' => true,
        ];

        $project = (new UpdateProject)->execute($request);

        $this->assertInstanceOf(
            Project::class,
            $project
        );

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'organization_id' => $user->organization_id,
            'name' => 'Dunder',
            'description' => 'this is a description',
            'is_public' => true,
        ]);
    }
}
