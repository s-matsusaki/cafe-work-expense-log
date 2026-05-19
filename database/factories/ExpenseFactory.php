<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Cafe;
use App\Models\User;
use App\Models\WorkSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
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
            'work_session_id' => null,
            'cafe_id' => null,
            'expense_date' => $this->faker->date(),
            'title' => $this->faker->randomElement(['コーヒー代', '書籍代', 'SaaS利用料']),
            'amount' => $this->faker->numberBetween(300, 5000),
            'expense_type' => $this->faker->randomElement(['cafe', 'book', 'saas', 'transport', 'other']),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'qr']),
            'accounting_recorded' => $this->faker->boolean(),
            'accounting_recorded_at' => null,
            'accounting_memo' => null,
            'memo' => $this->faker->sentence(),
        ];
    }

    public function withCafe(): static
    {
        return $this->state(fn (array $attributes) => [
            'cafe_id' => Cafe::factory(),
        ]);
    }

    public function withWorkSession(): static
    {
        return $this->state(fn (array $attributes) => [
            'work_session_id' => WorkSession::factory(),
        ]);
    }
}
