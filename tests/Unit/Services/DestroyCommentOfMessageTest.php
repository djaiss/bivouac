<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use App\Services\DestroyCommentOfMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyCommentOfMessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_comment(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);
        $this->executeService($user, $message, $comment);
    }

    /** @test */
    public function it_fails_if_project_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $message, $comment);
    }

    /** @test */
    public function it_fails_if_comment_doesnt_belong_to_message(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $message, $comment);
    }

    /** @test */
    public function it_fails_if_message_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $message, $comment);
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
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $message, $comment);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyCommentOfMessage)->execute($request);
    }

    private function executeService(User $user, Message $message, Comment $comment): void
    {
        $request = [
            'user_id' => $user->id,
            'message_id' => $message->id,
            'comment_id' => $comment->id,
        ];

        (new DestroyCommentOfMessage)->execute($request);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
