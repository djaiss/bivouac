<?php

namespace Tests\Unit\Services;

use App\Models\Comment;
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
        $comment = Comment::factory()->create([
            'organization_id' => $user->organization_id,
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
    public function it_fails_if_comment_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
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
