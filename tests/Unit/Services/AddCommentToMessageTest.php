<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Services\AddCommentToMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AddCommentToMessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_comment(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $user->projects()->attach($project->id);
        $this->executeService($user, $message);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new AddCommentToMessage)->execute($request);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $user->projects()->attach($project->id);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(ModelNotFoundException::class);

        $this->executeService($user, $message);
    }

    /** @test */
    public function it_fails_if_user_cant_access_the_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $this->expectException(NotEnoughPermissionException::class);

        $this->executeService($user, $message);
    }

    /** @test */
    public function it_fails_if_message_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $message = Message::factory()->create();
        $this->expectException(ModelNotFoundException::class);

        $this->executeService($user, $message);
    }

    private function executeService(User $user, Message $message): void
    {
        $request = [
            'user_id' => $user->id,
            'message_id' => $message->id,
            'body' => 'Dunder',
        ];

        $comment = (new AddCommentToMessage)->execute($request);

        $this->assertInstanceOf(
            Comment::class,
            $comment
        );

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'organization_id' => $user->organization_id,
            'body' => 'Dunder',
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);

        $this->assertDatabaseHas('message_read_status', [
            'user_id' => $user->id,
            'message_id' => $message->id,
        ]);
    }
}
