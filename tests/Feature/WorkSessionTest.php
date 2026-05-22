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
                'work_date' => '2026-05-19 00:00:00',
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
            'work_date' => '2026-05-19',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('work-sessions.show', $workSession));

        $response->assertOK();
        $response->assertSee('2026-05-19（火）');
    }

    public function test_work_sessions_index_shows_work_date_with_weekday(): void
    {
        $user = User::factory()->create();

        WorkSession::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-19',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('work-sessions.index'));

        $response->assertOk();
        $response->assertSee('2026-05-19（火）');
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

    public function test_authenticated_user_can_update_own_work_session(): void
    {
        $user = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $user->id,
        ]);

        $workSession = WorkSession::factory()->create([
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
            'title' => 'Before Work',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('work-sessions.update', $workSession), [
                'cafe_id' => $cafe->id,
                'work_date' => '2026-05-20 00:00:00',
                'title' => 'After Work',
                'work_minutes' => 180,
                'category' => 'study',
                'memo' => '更新後メモ',
            ]);

        $response->assertRedirect(route('work-sessions.show', $workSession));

        $this->assertDatabaseHas('work_sessions', [
            'id' => $workSession->id,
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
            'work_date' => '2026-05-20 00:00:00',
            'title' => 'After Work',
            'work_minutes' => 180,
            'category' => 'study',
            'memo' => '更新後メモ',
        ]);
    }

    public function test_user_cannot_update_other_users_work_session(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherCafe = Cafe::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $workSession = WorkSession::factory()->create([
            'user_id' => $otherUser->id,
            'cafe_id' => $otherCafe->id,
            'title' => 'Other User Work',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('work-sessions.update', $workSession), [
                'cafe_id' => $otherCafe->id,
                'work_date' => '2026-05-20 00:00:00',
                'title' => 'Updated By Wrong User',
                'work_minutes' => 999,
                'category' => 'invalid',
                'memo' => '不正更新',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('work_sessions', [
            'id' => $workSession->id,
            'user_id' => $otherUser->id,
            'title' => 'Other User Work',
        ]);
    }

    public function test_authenticated_user_can_delete_own_work_session(): void
    {
        $user = User::factory()->create();

        $workSession = WorkSession::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('work-sessions.destroy', $workSession));

        $response->assertRedirect(route('work-sessions.index'));

        $this->assertDatabaseMissing('work_sessions', [
            'id' => $workSession->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_work_session(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $workSession = WorkSession::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('work-sessions.destroy', $workSession));

        $response->assertForbidden();

        $this->assertDatabaseHas('work_sessions', [
            'id' => $workSession->id,
            'user_id' => $otherUser->id,
        ]);
    }
}
