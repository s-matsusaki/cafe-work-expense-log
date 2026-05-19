<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_books_index(): void
    {
        $response = $this->get(route('books.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_books_index(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('books.index'));

        $response->assertOk();
    }

    public function test_authenticated_user_can_create_book(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'Laravel実践開発',
                'purchased_on' => '2026-05-19 00:00:00',
                'status' => 'reading',
                'memo' => 'テスト用書籍メモ',
            ]);

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'user_id' => $user->id,
            'title' => 'Laravel実践開発',
            'purchased_on' => '2026-05-19 00:00:00',
            'status' => 'reading',
            'memo' => 'テスト用書籍メモ',
        ]);
    }

    public function test_user_can_view_own_book(): void
    {
        $user = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('books.show', $book));

        $response->assertOk();
    }

    public function test_user_cannot_view_other_users_book(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('books.show', $book));

        $response->assertForbidden();
    }

    public function test_authenticated_user_can_update_own_book(): void
    {
        $user = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
            'title' => 'Before Title',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('books.update', $book), [
                'title' => 'After Title',
                'purchased_on' => '2026-05-20 00:00:00',
                'status' => 'done',
                'memo' => '更新後メモ',
            ]);

        $response->assertRedirect(route('books.show', $book));

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'user_id' => $user->id,
            'title' => 'After Title',
            'purchased_on' => '2026-05-20 00:00:00',
            'status' => 'done',
            'memo' => '更新後メモ',
        ]);
    }

    public function test_user_cannot_update_other_users_book(): void
    {
        $user =User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Other User Book',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('books.update', $book), [
                'title' => 'Updated By Wrong User',
                'purchased_on' => '2026-05-20 00:00:00',
                'status' => 'done',
                'memo' => '不正更新',
            ]);
        
        $response->assertForbidden();

        $this->assertDatabaseHas('books', [
            'user_id' => $otherUser->id,
            'title' => 'Other User Book',
        ]);
    }

    public function test_authenticated_user_can_delete_own_book(): void
    {
        $user = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('books.destroy', $book));
        
        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseMissing('books',[
            'id' => $book->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_book(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('books.destroy', $book));

        $response->assertForbidden();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'user_id' => $otherUser->id,
        ]);
    }
}
