<?php

namespace Tests\Unit\Models;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_one_project(): void
    {
        $message = Message::factory()->create();

        $this->assertTrue($message->project()->exists());
    }

    /** @test */
    public function it_belongs_to_one_creator(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create([
            'created_by_user_id' => $user->id,
        ]);

        $this->assertTrue($message->creator()->exists());
    }

    /** @test */
    public function it_gets_the_author(): void
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $message = Message::factory()->create([
            'created_by_user_id' => null,
            'created_by_user_name' => 'Henri Troyat',
        ]);

        $this->assertEquals(
            'Henri Troyat',
            $message->authorName
        );

        $message->created_by_user_id = $user->id;
        $message->save();

        $this->assertEquals(
            'John Doe',
            $message->authorName
        );
    }
}
