<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cafe;
use App\Models\User;
use App\Models\WorkSession;

class WorkSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_work_sessions_index(): void
    {
        $response = $this->get(route('work-sessions.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_work_sessions_index(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('work-sessions.index'));
        
        $response->assertOk();
    }

    public function test_authenticated_user_can_create_work_session(): void
    {
        $user = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('work-sessions.store'), [
                'cafe_id' => $cafe->id,
                'work_date' => '2026-05-19',
                'title' => 'Laravelテスト実装',
                'work_minutes' => 120,
                'category' => 'development',
                'memo' => 'Feature Testを追加',
            ]);
        
        $response->assertRedirect(route('work-sessions.index'));

        $this->assertDatabaseHas('work_sessions', [
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
            'work_date' => '2026-05-19 00:00:00',
            'title' => 'Laravelテスト実装',
            'work_minutes' => 120,
            'category' => 'development',
            'memo' => 'Feature Testを追加',
        ]);
    }

    public function test_user_can_view_own_work_session(): void
    {
        $user = User::factory()->create();

        $workSession = WorkSession::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('work-sessions.show', $workSession));

        $response->assertOK();
    }

    public function test_user_cannnot_view_other_users_work_session(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $workSession = WorkSession::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('work-sessions.show', $workSession));

        $response->assertForbidden();
    }
}
