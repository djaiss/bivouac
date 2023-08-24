<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Project;
use App\Models\User;
use App\Services\RemoveMemberFromProject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RemoveMemberFromProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_a_member_from_a_project(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $member->projects()->attach($project->id);

        $this->executeService($user, $member, $project);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new RemoveMemberFromProject)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create();
        $user->projects()->attach($project->id);
        $member->projects()->attach($project->id);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $member, $project);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $member->projects()->attach($project->id);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $member, $project);
    }

    /** @test */
    public function it_fails_if_user_is_not_part_of_the_project(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $user->projects()->attach($project->id);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $member, $project);
    }

    /** @test */
    public function it_fails_if_member_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $member = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $user->projects()->attach($project->id);
        $member->projects()->attach($project->id);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $member, $project);
    }

    private function executeService(User $user, User $member, Project $project): void
    {
        $request = [
            'user_id' => $user->id,
            'member_id' => $member->id,
            'project_id' => $project->id,
        ];

        (new RemoveMemberFromProject)->execute($request);

        $this->assertDatabaseMissing('project_user', [
            'user_id' => $member->id,
            'project_id' => $project->id,
        ]);
    }
}
