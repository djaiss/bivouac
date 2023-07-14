<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Services\CreateMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateMessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_message(): void
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
        (new CreateMessage)->execute($request);
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
        ]);
        $this->expectException(NotEnoughPermissionException::class);

        $this->executeService($user, $project);
    }

    private function executeService(User $user, Project $project): void
    {
        $request = [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'title' => 'Dunder',
            'body' => 'this is a description',
        ];

        $message = (new CreateMessage)->execute($request);

        $this->assertInstanceOf(
            Message::class,
            $message
        );

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'project_id' => $project->id,
            'title' => 'Dunder',
            'body' => 'this is a description',
        ]);

        $this->assertDatabaseHas('message_read_status', [
            'user_id' => $user->id,
            'message_id' => $message->id,
        ]);

        $this->assertDatabaseHas('task_lists', [
            'organization_id' => $user->organization_id,
            'project_id' => $project->id,
            'name' => null,
            'tasklistable_id' => $message->id,
            'tasklistable_type' => Message::class,
        ]);
    }
}
