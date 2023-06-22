<?php

namespace Tests\Unit\Services;

use App\Models\Office;
use App\Models\User;
use App\Services\DestroyOffice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class DestroyOfficeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_an_office(): void
    {
        $user = User::factory()->create();
        $office = Office::factory()->create([
            'organization_id' => $user->organization_id,
        ]);
        $this->executeService($user, $office);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Ross',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyOffice)->execute($request);
    }

    /** @test */
    public function it_fails_if_office_doesnt_belong_to_organization(): void
    {
        $user = User::factory()->create();
        $office = Office::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($user, $office);
    }

    private function executeService(User $user, Office $office): void
    {
        $request = [
            'user_id' => $user->id,
            'office_id' => $office->id,
        ];

        (new DestroyOffice)->execute($request);

        $this->assertDatabaseMissing('offices', [
            'id' => $office->id,
        ]);
    }
}
