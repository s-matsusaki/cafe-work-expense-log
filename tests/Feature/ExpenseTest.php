<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cafe;
use App\Models\Expense;
use App\Models\User;
use App\Models\WorkSession;
use App\Models\Book;

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
            'expense_date' => '2026-05-19',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('expenses.show', $expense));

        $response->assertOk();
        $response->assertSee('2026-05-19（火）');
    }

    public function test_expenses_index_shows_expense_date_with_weekday(): void
    {
        $user = User::factory()->create();

        Expense::factory()->create([
            'user_id' => $user->id,
            'expense_date' => '2026-05-19',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('expenses.index'));

        $response->assertOk();
        $response->assertSee('2026-05-19（火）');
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

    public function test_authenticated_user_can_create_expense_with_book(): void
    {
        $user = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
            'title' => 'Laravel実践開発',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('expenses.store'), [
                'expense_date' => '2026-05-19 00:00:00',
                'title' => 'Laravel本購入',
                'amount' => 3200,
                'expense_type' => 'book',
                'payment_method' => 'card',
                'cafe_id' => null,
                'work_session_id' => null,
                'book_id' => $book->id,
                'accounting_recorded' => '0',
                'accounting_recorded_at' => null,
                'accounting_memo' => null,
                'memo' => '書籍代として登録',
            ]);

        $response->assertRedirect(route('expenses.index'));

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'title' => 'Laravel本購入',
            'amount' => 3200,
            'expense_type' => 'book',
            'payment_method' => 'card',
            'accounting_recorded' => false,
            'memo' => '書籍代として登録',
        ]);
    }

    public function test_user_cannot_create_expense_with_other_users_book(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherUsersBook = Book::factory()->create([
            'user_id' => $otherUser->id,
            'title' => '他人の本',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('expenses.store'), [
                'expense_date' => '2026-05-19 00:00:00',
                'title' => '不正な書籍代',
                'amount' => 3200,
                'expense_type' => 'book',
                'payment_method' => 'card',
                'cafe_id' => null,
                'work_session_id' => null,
                'book_id' => $otherUsersBook->id,
                'accounting_recorded' => '0',
                'accounting_recorded_at' => null,
                'accounting_memo' => null,
                'memo' => '他人の本を指定',
            ]);

        $response->assertSessionHasErrors('book_id');

        $this->assertDatabaseMissing('expenses', [
            'user_id' => $user->id,
            'book_id' => $otherUsersBook->id,
            'title' => '不正な書籍代',
        ]);
    }

    public function test_authenticated_user_can_update_expense_book(): void
    {
        $user = User::factory()->create();

        $oldBook = Book::factory()->create([
            'user_id' => $user->id,
            'title' => '古い本',
        ]);

        $newBook = Book::factory()->create([
            'user_id' => $user->id,
            'title' => '新しい本',
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'book_id' => $oldBook->id,
            'title' => '書籍代',
            'expense_type' => 'book',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('expenses.update', $expense), [
                'expense_date' => '2026-05-20 00:00:00',
                'title' => '書籍代更新',
                'amount' => 4500,
                'expense_type' => 'book',
                'payment_method' => 'card',
                'cafe_id' => null,
                'work_session_id' => null,
                'book_id' => $newBook->id,
                'accounting_recorded' => '1',
                'accounting_recorded_at' => '2026-05-20 12:00',
                'accounting_memo' => 'freeeに登録済み',
                'memo' => '関連書籍を変更',
            ]);

        $response->assertRedirect(route('expenses.show', $expense));

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'user_id' => $user->id,
            'book_id' => $newBook->id,
            'title' => '書籍代更新',
            'amount' => 4500,
            'expense_type' => 'book',
            'accounting_recorded' => true,
            'memo' => '関連書籍を変更',
        ]);
    }

    public function test_user_cannot_update_expense_with_other_users_book(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownBook = Book::factory()->create([
            'user_id' => $user->id,
        ]);

        $otherUsersBook = Book::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'book_id' => $ownBook->id,
            'title' => '自分の支出',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('expenses.update', $expense), [
                'expense_date' => '2026-05-20',
                'title' => '不正な更新',
                'amount' => 9999,
                'expense_type' => 'book',
                'payment_method' => 'card',
                'cafe_id' => null,
                'work_session_id' => null,
                'book_id' => $otherUsersBook->id,
                'accounting_recorded' => '1',
                'accounting_recorded_at' => '2026-05-20 12:00',
                'accounting_memo' => '不正更新',
                'memo' => '他人の本を指定',
            ]);

        $response->assertSessionHasErrors('book_id');

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'user_id' => $user->id,
            'book_id' => $ownBook->id,
            'title' => '自分の支出',
        ]);

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
            'book_id' => $otherUsersBook->id,
        ]);
    }
}
