<?php

namespace Database\Factories;

use App\Models\Cafe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cafe>
 */
class CafeFactory extends Factory
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
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'nearest_station' => $this->faker->city() . '駅',
            'memo' => $this->faker->sentence(),
        ];
    }
}
