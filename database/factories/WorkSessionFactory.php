<?php

namespace Database\Factories;

use App\Models\WorkSession;
use App\Models\Cafe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkSession>
 */
class WorkSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'cafe_id' => Cafe::factory(),
            'work_date' => $this->faker->date(),
            'start_time' => null,
            'end_time' => null,
            'title' => $this->faker->sentence(3),
            'work_minutes' => $this->faker->numberBetween(30, 240),
            'category' => $this->faker->randomElement(['development', 'study', 'reading']),
            'memo' => $this->faker->sentence(),
        ];
    }
}
