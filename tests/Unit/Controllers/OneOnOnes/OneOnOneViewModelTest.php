<?php

namespace Tests\Unit\Controllers\OneOnOnes;

use App\Http\Controllers\OneOnOnes\OneOnOneViewModel;
use App\Models\OneOnOne;
use App\Models\OneOnOneEntry;
use App\Models\User;
use Carbon\Carbon;
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

        $this->assertCount(4, $array);
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
                'url' => [
                    'show' => env('APP_URL') . '/oneonones/' . $oneOnOne->id,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_data_needed_for_the_show_view(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $userNotAlreadyInOneOnOne = User::factory()->create([
            'organization_id' => $user->organization_id,
        ]);

        $oneOnOne = OneOnOne::factory()->create([
            'user_id' => $user->id,
            'other_user_id' => $otherUser->id,
        ]);

        $array = OneOnOneViewModel::show($oneOnOne);

        $this->assertCount(4, $array);
        $this->assertArrayHasKey('one_on_one', $array);
        $this->assertArrayHasKey('active_points_of_discussion', $array);
        $this->assertArrayHasKey('inactive_points_of_discussion', $array);
        $this->assertArrayHasKey('url', $array);

        $this->assertEquals(
            [
                'index' => env('APP_URL') . '/oneonones',
                'store' => env('APP_URL') . '/oneonones/' . $oneOnOne->id . '/entries',
                'breadcrumb' => [
                    'oneonones' => env('APP_URL') . '/oneonones',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_data_needed_for_the_dto_of_entry(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $oneOnOneEntry = OneOnOneEntry::factory()->create([
            'body' => 'Ross',
            'checked_at' => null,
        ]);

        $array = OneOnOneViewModel::dtoEntry($oneOnOneEntry);

        $this->assertCount(5, $array);

        $this->assertEquals(
            [
                'id' => $oneOnOneEntry->id,
                'body' => 'Ross',
                'written_at' => '2018-01-01',
                'checked' => false,
                'url' => [
                    'update' => env('APP_URL') . '/oneonones/' . $oneOnOneEntry->one_on_one_id . '/entries/' . $oneOnOneEntry->id,
                    'toggle' => env('APP_URL') . '/oneonones/' . $oneOnOneEntry->one_on_one_id . '/entries/' . $oneOnOneEntry->id . '/toggle',
                    'destroy' => env('APP_URL') . '/oneonones/' . $oneOnOneEntry->one_on_one_id . '/entries/' . $oneOnOneEntry->id,
                ],
            ],
            $array
        );
    }
}
