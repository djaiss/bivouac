<?php

namespace Tests\Unit\Services;

use App\Models\Organization;
use App\Models\User;
use App\Services\ActivateLicenceKey;
use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ActivateLicenceKeyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_activates_a_licence_key(): void
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
        (new ActivateLicenceKey)->execute($request);
    }

    private function executeService(): void
    {
        $user = User::factory()->create([
            'invitation_code' => 'testtest',
        ]);
        $request = [
            'user_id' => $user->id,
            'licence_key' => 'C9382352-16CA-4CD3-8A17-DA918C66CF5F',
        ];

        $body = file_get_contents(base_path('tests/Unit/Fixtures/ActivateLicenceKeyValidResponse.json'));
        Http::fake([
            'https://api.lemonsqueezy.com/v1/licenses/activate' => Http::response($body, 200),
        ]);

        $organization = (new ActivateLicenceKey)->execute($request);

        $this->assertInstanceOf(
            Organization::class,
            $organization
        );

        $this->assertDatabaseHas('organizations', [
            'id' => $organization->id,
            'licence_key' => 'C9382352-16CA-4CD3-8A17-DA918C66CF5F',
        ]);

        $body = file_get_contents(base_path('tests/Unit/Fixtures/ActivateLicenceKeyInvalidResponse.json'));
        Http::fake([
            'https://api.lemonsqueezy.com/v1/licenses/activate' => Http::response($body, 200),
        ]);

        $this->expectException(Exception::class);
        (new ActivateLicenceKey)->execute($request);
    }
}
