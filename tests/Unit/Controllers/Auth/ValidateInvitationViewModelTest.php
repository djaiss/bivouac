<?php

namespace Tests\Unit\Controllers\Auth;

use App\Http\Controllers\Auth\ValidateInvitationViewModel;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ValidateInvitationViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_view(): void
    {
        $user = User::factory()->create([
            'invitation_code' => 'john',
        ]);

        $array = ValidateInvitationViewModel::data($user);

        $this->assertEquals(
            [
                'url' => [
                    'store' => env('APP_URL') . '/invitation/john',
                ],
            ],
            $array
        );
    }
}
