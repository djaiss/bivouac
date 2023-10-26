<?php

namespace Tests\Unit\Jobs;

use App\Jobs\RecordVisit;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RecordVisitTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_records_a_visit(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();

        RecordVisit::dispatch($project, $user);

        $this->assertDatabaseHas('project_visits', [
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);
    }
}
