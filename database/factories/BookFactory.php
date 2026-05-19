<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
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
            'title' => $this->faker->sentence(3),
            'purchased_on' => $this->faker->optional()->date(),
            'status' => $this->faker->randomElement(['unread', 'reading', 'done', 'paused']),
            'memo' => $this->faker->optional()->sentence(),
        ];
    }
}
