<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_book_summary_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        Book::factory()->create([
            'user_id' => $user->id,
            'title' => '読書中の本',
            'status' => 'reading',
        ]);

        Book::factory()->create([
            'user_id' => $user->id,
            'title' => '未読の本',
            'status' => 'unread',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();

        $response->assertSee('読書中の書籍');
        $response->assertSee('1冊');
        $response->assertSee('未読の書籍');
        $response->assertSee('読書中の本');
        $response->assertSee('未読の本');
    }

    public function test_dashboard_does_not_show_other_users_books(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Book::factory()->create([
            'user_id' => $otherUser->id,
            'title' => '他人の本',
            'status' => 'reading',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('他人の本');
    }
}
