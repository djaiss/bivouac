<?php

namespace Tests\Unit\Jobs;

use App\Jobs\PopulateAccount;
use App\Models\Organization;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PopulateAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_populates_an_account(): void
    {
        $organization = Organization::factory()->create();
        PopulateAccount::dispatch($organization);

        $this->assertEquals(
            13,
            DB::table('roles')->count()
        );
    }
}
