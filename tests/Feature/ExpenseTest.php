<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cafe;
use App\Models\Expense;
use App\Models\User;
use App\Models\WorkSession;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_expenses_index(): void
    {
        $response = $this->get(route('expenses.index'));

        $response->assertRedirect('login');
    }

    public function test_authenticated_user_can_access_expenses_index(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('expenses.index'));

        $response->assertOk();
    }

    public function test_authenticated_user_can_create_expense(): void
    {
        $user = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $user->id,
        ]);

        $workSession = WorkSession::factory()->create([
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('expenses.store', [
                'expense_date' => '2026-05-19',
                'title' => 'コーヒー代',
                'amount' => 680,
                'expense_type' => 'cafe',
                'payment_method' => 'card',
                'cafe_id' => $cafe->id,
                'work_session_id' => $workSession->id,
                'accounting_recorded' => '1',
                'accounting_recorded_at' => '2026-05-19 12:00',
                'accounting_memo' => 'freeeに登録済み',
                'memo' => '作業時のカフェ代',
            ]));

        $response->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', [
                'expense_date' => '2026-05-19 00:00:00',
                'title' => 'コーヒー代',
                'amount' => 680,
                'expense_type' => 'cafe',
                'payment_method' => 'card',
                'cafe_id' => $cafe->id,
                'work_session_id' => $workSession->id,
                'accounting_recorded' => '1',
                'accounting_recorded_at' => '2026-05-19 12:00:00',
                'accounting_memo' => 'freeeに登録済み',
                'memo' => '作業時のカフェ代',
        ]);
    }

    public function test_user_can_view_own_expense(): void
    {
        $user = User::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('expenses.show', $expense));

        $response->assertOk();
    }

    public function test_user_cannot_view_other_users_expense(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('expenses.show', $expense));
        
        $response->assertForbidden();
    }
}
