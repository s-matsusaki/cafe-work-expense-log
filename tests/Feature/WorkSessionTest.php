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
                'start_time_hour' => '09',
                'start_time_minute' => '00',
                'end_time_hour' => '11',
                'end_time_minute' => '00',
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
            'start_time' => '09:00',
            'end_time' => '11:00',
            'title' => 'Laravelテスト実装',
            'work_minutes' => 120,
            'category' => 'development',
            'memo' => 'Feature Testを追加',
        ]);
    }

    public function test_work_minutes_are_calculated_from_time_range_when_blank(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('work-sessions.store'), [
                'work_date' => '2026-05-19',
                'start_time_hour' => '09',
                'start_time_minute' => '30',
                'end_time_hour' => '12',
                'end_time_minute' => '00',
                'title' => 'Auto Calculated Work',
                'work_minutes' => '',
            ]);

        $response->assertRedirect(route('work-sessions.index'));

        $this->assertDatabaseHas('work_sessions', [
            'user_id' => $user->id,
            'start_time' => '09:30',
            'end_time' => '12:00',
            'title' => 'Auto Calculated Work',
            'work_minutes' => 150,
        ]);
    }

    public function test_user_can_view_own_work_session(): void
    {
        $user = User::factory()->create();

        $workSession = WorkSession::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-19',
            'start_time' => '09:00',
            'end_time' => '11:30',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('work-sessions.show', $workSession));

        $response->assertOK();
        $response->assertSee('2026-05-19（火）');
        $response->assertSee('09:00〜11:30');
    }

    public function test_work_sessions_index_shows_work_date_with_weekday(): void
    {
        $user = User::factory()->create();

        WorkSession::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-19',
            'start_time' => '09:00',
            'end_time' => '11:30',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('work-sessions.index'));

        $response->assertOk();
        $response->assertSee('2026-05-19（火）');
        $response->assertSee('09:00〜11:30');
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
                'start_time_hour' => '14',
                'start_time_minute' => '00',
                'end_time_hour' => '17',
                'end_time_minute' => '00',
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
            'start_time' => '14:00',
            'end_time' => '17:00',
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
                'work_minutes' => 990,
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

    public function test_work_session_end_time_must_be_after_start_time(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('work-sessions.store'), [
                'work_date' => '2026-05-19',
                'start_time' => '18:00',
                'end_time' => '17:00',
                'title' => 'Invalid Time Range',
                'work_minutes' => 60,
            ]);

        $response->assertSessionHasErrors('end_time');
    }

    public function test_work_session_times_must_be_in_ten_minute_steps(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('work-sessions.store'), [
                'work_date' => '2026-05-19',
                'start_time' => '09:05',
                'end_time' => '11:15',
                'title' => 'Invalid Time Step',
                'work_minutes' => 60,
            ]);

        $response->assertSessionHasErrors(['start_time', 'end_time']);
    }

    public function test_work_session_time_parts_must_be_completed_together(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('work-sessions.store'), [
                'work_date' => '2026-05-19',
                'start_time_hour' => '09',
                'start_time_minute' => '',
                'title' => 'Incomplete Time Parts',
                'work_minutes' => 60,
            ]);

        $response->assertSessionHasErrors('start_time');
    }

    public function test_work_minutes_must_be_multiple_of_ten(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('work-sessions.store'), [
                'work_date' => '2026-05-19',
                'title' => 'Invalid Work Minutes',
                'work_minutes' => 15,
            ]);

        $response->assertSessionHasErrors('work_minutes');
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
