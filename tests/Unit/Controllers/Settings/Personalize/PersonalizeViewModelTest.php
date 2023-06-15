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
        $array = PersonalizeViewModel::data();

        $this->assertEquals(
            [
                'url' => [
                    'users' => env('APP_URL') . '/settings/personalize/users',
                ],
            ],
            $array
        );
    }
}
