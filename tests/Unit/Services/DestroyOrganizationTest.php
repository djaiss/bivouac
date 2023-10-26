<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\DestroyOrganization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DestroyOrganizationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_the_organization(): void
    {
        $author = User::factory()->create();
        $this->be($author);

        (new DestroyOrganization)->execute();

        $this->assertDatabaseMissing('organizations', [
            'id' => $author->organization_id,
        ]);
    }
}
