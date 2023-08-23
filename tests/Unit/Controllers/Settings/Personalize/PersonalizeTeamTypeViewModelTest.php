<?php

namespace Tests\Unit\Controllers\Settings\Personalize;

use App\Http\Controllers\Settings\Personalize\PersonalizeTeamTypeViewModel;
use App\Models\Organization;
use App\Models\TeamType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PersonalizeTeamTypeViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $organization = Organization::factory()->create();
        $array = PersonalizeTeamTypeViewModel::index($organization);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('team_types', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'create' => env('APP_URL') . '/settings/teamTypes/create',
                'breadcrumb' => [
                    'home' => env('APP_URL') . '/profile',
                    'settings' => env('APP_URL') . '/settings/personalize',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_dto_for_team_type(): void
    {
        $teamType = TeamType::factory()->create([
            'label' => 'Dunder',
            'position' => 1,
        ]);
        $array = PersonalizeTeamTypeViewModel::dto($teamType);

        $this->assertCount(4, $array);
        $this->assertEquals(
            [
                'id' => $teamType->id,
                'label' => 'Dunder',
                'position' => 1,
                'url' => [
                    'edit' => env('APP_URL') . '/settings/teamTypes/' . $teamType->id . '/edit',
                    'destroy' => env('APP_URL') . '/settings/teamTypes/' . $teamType->id,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_data_for_the_create_view(): void
    {
        $array = PersonalizeTeamTypeViewModel::create();

        $this->assertCount(1, $array);
        $this->assertEquals(
            [
                'url' => [
                    'store' => env('APP_URL') . '/settings/teamTypes',
                    'breadcrumb' => [
                        'home' => env('APP_URL') . '/profile',
                        'settings' => env('APP_URL') . '/settings/personalize',
                        'team_types' => env('APP_URL') . '/settings/teamTypes',
                    ],
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_data_for_the_edit_view(): void
    {
        $teamType = TeamType::factory()->create([
            'label' => 'Dunder',
            'position' => 1,
        ]);
        $array = PersonalizeTeamTypeViewModel::edit($teamType);

        $this->assertCount(4, $array);
        $this->assertEquals(
            [
                'id' => $teamType->id,
                'label' => 'Dunder',
                'position' => 1,
                'url' => [
                    'update' => env('APP_URL') . '/settings/teamTypes/' . $teamType->id,
                    'breadcrumb' => [
                        'home' => env('APP_URL') . '/profile',
                        'settings' => env('APP_URL') . '/settings/personalize',
                        'team_types' => env('APP_URL') . '/settings/teamTypes',
                    ],
                ],
            ],
            $array
        );
    }
}
