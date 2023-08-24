<?php

namespace Tests\Unit\Controllers\Settings\Personalize;

use App\Http\Controllers\Settings\Personalize\PersonalizeUpgradeViewModel;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PersonalizeUpgradeViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_view(): void
    {
        $organization = Organization::factory()->create([
            'licence_key' => '1234567890',
        ]);
        $array = PersonalizeUpgradeViewModel::data($organization);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('upgraded', $array);
        $this->assertArrayHasKey('url', $array);
        $this->assertEquals(
            [
                'upgraded' => true,
                'url' => [
                    'store' => 'https://bivouac.lemonsqueezy.com/',
                    'upgrade' => env('APP_URL') . '/settings/upgrade',
                    'breadcrumb' => [
                        'home' => env('APP_URL') . '/profile',
                        'settings' => env('APP_URL') . '/settings',
                    ],
                ],
            ],
            $array
        );
    }
}
