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

        public function test_authenticated_user_can_update_own_expense(): void
    {
        $user = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $user->id,
        ]);

        $workSession = WorkSession::factory()->create([
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
            'work_session_id' => $workSession->id,
            'title' => 'Before Expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('expenses.update', $expense), [
                'expense_date' => '2026-05-20 00:00:00',
                'title' => 'After Expense',
                'amount' => 1200,
                'expense_type' => 'cafe',
                'payment_method' => 'cash',
                'cafe_id' => $cafe->id,
                'work_session_id' => $workSession->id,
                'accounting_recorded' => '1',
                'accounting_recorded_at' => '2026-05-20 12:00',
                'accounting_memo' => '更新後会計メモ',
                'memo' => '更新後メモ',
            ]);

        $response->assertRedirect(route('expenses.show', $expense));

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
            'work_session_id' => $workSession->id,
            'expense_date' => '2026-05-20 00:00:00',
            'title' => 'After Expense',
            'amount' => 1200,
            'expense_type' => 'cafe',
            'payment_method' => 'cash',
            'accounting_recorded' => true,
            'accounting_memo' => '更新後会計メモ',
            'memo' => '更新後メモ',
        ]);
    }

    public function test_user_cannot_update_other_users_expense(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Other User Expense',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('expenses.update', $expense), [
                'expense_date' => '2026-05-20 00:00:00',
                'title' => 'Updated By Wrong User',
                'amount' => 9999,
                'expense_type' => 'other',
                'payment_method' => 'cash',
                'cafe_id' => null,
                'work_session_id' => null,
                'accounting_recorded' => '1',
                'accounting_recorded_at' => '2026-05-20 12:00',
                'accounting_memo' => '不正更新',
                'memo' => '不正更新',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'user_id' => $otherUser->id,
            'title' => 'Other User Expense',
        ]);
    }

    public function test_authenticated_user_can_delete_own_expense(): void
    {
        $user = User::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('expenses.destroy', $expense));

        $response->assertRedirect(route('expenses.index'));

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_expense(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $expense = Expense::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('expenses.destroy', $expense));

        $response->assertForbidden();

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'user_id' => $otherUser->id,
        ]);
    }
}
