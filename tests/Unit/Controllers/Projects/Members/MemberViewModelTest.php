<?php

namespace Tests\Unit\Controllers\Projects\Members;

use App\Http\Controllers\Projects\Members\MemberViewModel;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MemberViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $project->users()->attach($user->id);
        $array = MemberViewModel::index($project);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('members', $array);
    }

    /** @test */
    public function it_gets_the_dto(): void
    {
        $user = User::factory()->create([
            'first_name' => 'Michael',
            'last_name' => 'Scott',
        ]);
        $array = MemberViewModel::dto($user);

        $this->assertCount(4, $array);
        $this->assertEquals([
            'id' => $user->id,
            'name' => 'Michael Scott',
            'avatar' => $user->avatar,
            'url' => env('APP_URL') . '/users/' . $user->id,
        ],
            $array
        );
    }
}
