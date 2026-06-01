<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */

class ReportFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'image' => $this->faker->imageUrl(),
            'type' => $this->faker->randomElement(['wild_dumps',]), //wild_dumps
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'status' => $this->faker->randomElement(['in_progress', 'done']),
            'description' => $this->faker->text(200),
        ];
    }
}

