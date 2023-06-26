<?php

namespace Tests\Unit\Traits;

use App\Models\TeamType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TranslatableTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_translates(): void
    {
        $teamType = TeamType::factory()->create([
            'label' => 'this is the real name',
            'label_translation_key' => 'life_event_category.label',
        ]);

        $this->assertEquals(
            'this is the real name',
            $teamType->label
        );

        $teamType = TeamType::factory()->create([
            'label' => null,
            'label_translation_key' => 'life_event_category.label',
        ]);

        $this->assertEquals(
            'life_event_category.label',
            $teamType->label
        );
    }
}
