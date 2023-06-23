<?php

namespace Tests\Unit\Controllers\Settings\Personalize;

use App\Http\Controllers\Settings\Personalize\PersonalizeOfficeViewModel;
use App\Models\Office;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PersonalizeOfficeViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $organization = Organization::factory()->create();
        $array = PersonalizeOfficeViewModel::index($organization);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('offices', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'create' => env('APP_URL') . '/settings/personalize/offices/create',
                'breadcrumb' => [
                    'home' => env('APP_URL') . '/profile',
                    'settings' => env('APP_URL') . '/settings/personalize',
                ],
            ],
            $array['url']
        );
    }

    /** @test */
    public function it_gets_the_dto_for_office(): void
    {
        $office = Office::factory()->create([
            'name' => 'Dunder',
            'is_main_office' => true,
        ]);
        $array = PersonalizeOfficeViewModel::dto($office);

        $this->assertCount(4, $array);
        $this->assertEquals(
            [
                'id' => $office->id,
                'name' => 'Dunder',
                'is_main_office' => true,
                'url' => [
                    'edit' => env('APP_URL') . '/settings/personalize/offices/' . $office->id . '/edit',
                    'destroy' => env('APP_URL') . '/settings/personalize/offices/' . $office->id,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_data_for_the_edit_view(): void
    {
        $office = Office::factory()->create([
            'name' => 'Dunder',
            'is_main_office' => true,
        ]);
        $array = PersonalizeOfficeViewModel::edit($office);

        $this->assertCount(4, $array);
        $this->assertEquals(
            [
                'id' => $office->id,
                'name' => 'Dunder',
                'is_main_office' => true,
                'url' => [
                    'update' => env('APP_URL') . '/settings/personalize/offices/' . $office->id,
                    'breadcrumb' => [
                        'home' => env('APP_URL') . '/profile',
                        'settings' => env('APP_URL') . '/settings/personalize',
                        'offices' => env('APP_URL') . '/settings/personalize/offices',
                    ],
                ],
            ],
            $array
        );
    }
}
