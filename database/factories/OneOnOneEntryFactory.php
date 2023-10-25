<?php

namespace Database\Factories;

use App\Models\OneOnOne;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OneOnOneEntry>
 */
class OneOnOneEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'one_on_one_id' => OneOnOne::factory(),
            'user_id' => User::factory(),
            'body' => $this->faker->sentence,
        ];
    }
}
