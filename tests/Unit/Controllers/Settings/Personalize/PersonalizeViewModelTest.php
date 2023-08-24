<?php

namespace Tests\Unit\Controllers\Settings\Personalize;

use App\Http\Controllers\Settings\Personalize\PersonalizeViewModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PersonalizeViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_view(): void
    {
        config(['app.store.activated' => true]);
        $array = PersonalizeViewModel::data();

        $this->assertEquals(
            [
                'upgradable' => true,
                'url' => [
                    'breadcrumb' => [
                        'home' => env('APP_URL') . '/profile',
                        'settings' => env('APP_URL') . '/settings',
                    ],
                    'users' => env('APP_URL') . '/settings/users',
                    'offices' => env('APP_URL') . '/settings/offices',
                    'team_types' => env('APP_URL') . '/settings/teamTypes',
                    'upgrade' => env('APP_URL') . '/settings/upgrade',
                ],
            ],
            $array
        );
    }
}
