<?php

namespace Tests\Unit\Services;

use App\Exceptions\NotEnoughPermissionException;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Project;
use App\Models\Reaction;
use App\Models\User;
use App\Services\AddReactionToComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AddReactionToCommentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_reaction(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $message = Message::factory()->create([
            'project_id' => $project->id,
        ]);
        $user->projects()->attach($project->id);
        $comment = Comment::factory()->create([
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);
        $this->executeService($user, $comment);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new AddReactionToComment)->execute($request);
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
        $comment = Comment::factory()->create([
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);
        $this->expectException(ModelNotFoundException::class);

        $this->executeService($user, $comment);
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
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($user, $comment);
    }

    /** @test */
    public function it_fails_if_message_doesnt_belong_to_project(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create();
        $comment = Comment::factory()->create([
            'commentable_id' => $message->id,
            'commentable_type' => Message::class,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $comment);
    }

    /** @test */
    public function it_fails_if_comment_doesnt_belong_to_message(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $user->projects()->attach($project->id);
        $comment = Comment::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $comment);
    }

    private function executeService(User $user, Comment $comment): void
    {
        $request = [
            'user_id' => $user->id,
            'comment_id' => $comment->id,
            'emoji' => '🥳',
        ];

        $reaction = (new AddReactionToComment)->execute($request);

        $this->assertInstanceOf(
            Reaction::class,
            $reaction
        );

        $this->assertDatabaseHas('reactions', [
            'id' => $reaction->id,
            'organization_id' => $user->organization_id,
            'emoji' => '🥳',
            'reactionable_id' => $comment->id,
            'reactionable_type' => Comment::class,
        ]);
    }
}
