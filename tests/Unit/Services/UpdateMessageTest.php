<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Services\UpdateMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateMessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_message(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->executeService($user, $project, $message);
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
        $this->executeService($user, $project, $message);
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
        $this->executeService($user, $project, $message);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
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
        $this->executeService($user, $project, $message);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateMessage)->execute($request);
    }

    private function executeService(User $user, Project $project, Message $message): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $request = [
            'user_id' => $user->id,
            'message_id' => $message->id,
            'title' => 'Dunder',
            'body' => 'this is a description',
        ];

        $message = (new UpdateMessage)->execute($request);

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

        $this->assertDatabaseHas('projects', [
            'updated_at' => '2018-01-01 00:00:00',
        ]);
    }
}
