<?php

namespace Tests\Unit\Controllers\OneOnOnes;

use App\Http\Controllers\OneOnOnes\OneOnOneViewModel;
use App\Models\OneOnOne;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OneOnOneViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $user = User::factory()->create();
        $array = OneOnOneViewModel::index($user);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('one_on_ones', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'create' => env('APP_URL') . '/oneonones/create',
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_data_needed_for_the_create_view(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $userNotAlreadyInOneOnOne = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);

        OneOnOne::factory()->create([
            'user_id' => $user->id,
            'other_user_id' => $otherUser->id,
        ]);

        $array = OneOnOneViewModel::create($user);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('users', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                0 => [
                    'id' => $userNotAlreadyInOneOnOne->id,
                    'name' => $userNotAlreadyInOneOnOne->name,
                    'avatar' => $userNotAlreadyInOneOnOne->avatar,
                ],
            ],
            $array['users']->toArray()
        );
        $this->assertEquals(
            [
                'store' => env('APP_URL') . '/oneonones',
                'breadcrumb' => [
                    'oneonones' => env('APP_URL') . '/oneonones',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_dto(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);

        $oneOnOne = OneOnOne::factory()->create([
            'user_id' => $user->id,
            'other_user_id' => $otherUser->id,
        ]);
        $array = OneOnOneViewModel::dtoOneOnOne($oneOnOne);

        $this->assertCount(3, $array);
        $this->assertEquals(
            [
                'id' => $oneOnOne->id,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                ],
                'other_user' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'avatar' => $otherUser->avatar,
                ],
            ],
            $array
        );
    }
}
