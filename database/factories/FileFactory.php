<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'uuid' => fake()->uuid,
            'original_url' => fake()->url,
            'cdn_url' => fake()->url,
            'name' => fake()->name,
            'mime_type' => 'avatar',
            'type' => 'avatar',
            'size' => fake()->numberBetween(),
        ];
    }
}
