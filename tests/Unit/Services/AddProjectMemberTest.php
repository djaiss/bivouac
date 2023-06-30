<?php

namespace Tests\Unit\Services;

use App\Models\Project;
use App\Models\User;
use App\Services\AddProjectMember;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AddProjectMemberTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_project_member(): void
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
        (new AddProjectMember)->execute($request);
    }

    private function executeService(User $user, Project $project): void
    {
        $request = [
            'user_id' => $user->id,
            'project_id' => $project->id,
        ];

        $project = (new AddProjectMember)->execute($request);

        $this->assertInstanceOf(
            Project::class,
            $project
        );

        $this->assertDatabaseHas('project_user', [
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);
    }
}
