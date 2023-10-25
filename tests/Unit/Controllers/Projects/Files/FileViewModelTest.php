<?php

namespace Tests\Unit\Controllers\Projects\Files;

use App\Http\Controllers\Projects\Files\FileViewModel;
use App\Models\Project;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FileViewModelTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_needed_for_the_index_view(): void
    {
        $project = Project::factory()->create();
        $array = FileViewModel::index($project);

        $this->assertCount(2, $array);
        $this->assertArrayHasKey('project', $array);
        $this->assertArrayHasKey('files', $array);
    }
}
