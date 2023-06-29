<?php

namespace Tests\Unit\Services;

use App\Models\Project;
use App\Models\User;
use App\Services\CreateProject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateProjectTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_project(): void
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
        (new CreateProject)->execute($request);
    }

    private function executeService(): void
    {
        $user = User::factory()->create();
        $request = [
            'user_id' => $user->id,
            'name' => 'Dunder',
            'description' => 'this is a description',
            'is_public' => true,
        ];

        $project = (new CreateProject)->execute($request);

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
