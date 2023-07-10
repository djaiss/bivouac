<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Services\MarkMessageAsRead;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MarkMessageAsReadTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_read_status_of_a_message(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($user, $message);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $message);
    }

    /** @test */
    public function it_fails_if_message_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $message);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project_and_project_is_private(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
            'is_public' => false,
        ]);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $message);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new MarkMessageAsRead)->execute($request);
    }

    private function executeService(User $user, Message $message): void
    {
        $request = [
            'user_id' => $user->id,
            'message_id' => $message->id,
        ];

        (new MarkMessageAsRead)->execute($request);

        $this->assertDatabaseHas('message_read_status', [
            'user_id' => $user->id,
            'message_id' => $message->id,
        ]);
    }
}
