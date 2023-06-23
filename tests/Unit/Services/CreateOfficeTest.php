<?php

namespace Tests\Unit\Services;

use App\Models\Office;
use App\Models\User;
use App\Services\CreateOffice;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateOfficeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_office(): void
    {
        $this->executeService();
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new CreateOffice)->execute($request);
    }

    private function executeService(): void
    {
        $user = User::factory()->create();
        $request = [
            'user_id' => $user->id,
            'name' => 'Dunder',
            'is_main_office' => true,
        ];

        $office = (new CreateOffice)->execute($request);

        $this->assertInstanceOf(
            Office::class,
            $office
        );

        $this->assertDatabaseHas('offices', [
            'id' => $office->id,
            'organization_id' => $user->organization_id,
            'name' => 'Dunder',
            'is_main_office' => true,
        ]);
    }
}
