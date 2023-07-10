<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReactionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_organization(): void
    {
        $reaction = Reaction::factory()->create();

        $this->assertTrue($reaction->organization()->exists());
    }

    /** @test */
    public function it_belongs_to_one_creator(): void
    {
        $user = User::factory()->create();
        $reaction = Reaction::factory()->create([
            'author_id' => $user->id,
        ]);

        $this->assertTrue($reaction->creator()->exists());
    }

    /** @test */
    public function it_gets_the_author(): void
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $reaction = Reaction::factory()->create([
            'author_id' => null,
            'author_name' => 'Henri Troyat',
        ]);

        $this->assertEquals(
            'Henri Troyat',
            $reaction->authorName
        );

        $reaction->author_id = $user->id;
        $reaction->save();

        $this->assertEquals(
            'John Doe',
            $reaction->authorName
        );
    }

    /** @test */
    public function it_gets_the_object_the_reaction_is_about(): void
    {
        $comment = Comment::factory()->create();
        $reaction = Reaction::factory()->create([
            'author_id' => null,
            'author_name' => 'Henri Troyat',
            'reactionable_id' => $comment->id,
            'reactionable_type' => Comment::class,
        ]);

        $this->assertInstanceOf(
            Comment::class,
            $reaction->reactionable
        );
    }
}
