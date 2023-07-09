<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'author_id' => User::factory(),
            'author_name' => fake()->name(),
            'body' => fake()->paragraph(),
            'commentable_id' => Project::factory(),
            'commentable_type' => '\App\Models\Project',
        ];
    }
}
