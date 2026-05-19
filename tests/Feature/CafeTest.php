<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cafe;
use App\Models\User;

class CafeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_cafes_index(): void
    {
        $response = $this->get(route('cafes.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_cafes_index(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('cafes.index'));
        
        $response->assertOk();
    }

    public function test_authenticated_user_can_create_cafe(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('cafes.store'),[
                'name' => 'Test Cafe',
                'address' => '福岡市博多区',
                'nearest_station' => '博多駅',
                'memo' => 'テスト用カフェ',
            ]);

        $response->assertRedirect(route('cafes.index'));

        $this->assertDatabaseHas('cafes', [
            'user_id' => $user->id,
            'name' => 'Test Cafe',
            'address' => '福岡市博多区',
            'nearest_station' => '博多駅',
            'memo' => 'テスト用カフェ',
        ]);
    }

    public function test_user_can_view_own_cafe(): void
    {
        $user = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('cafes.show', $cafe));

        $response->assertOk();
    }

    public function test_user_cannnot_view_other_users_cafe(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('cafes.show', $cafe));

        $response->assertForbidden();
    }

    public function test_authenticated_user_can_update_own_cafe(): void
    {
        $user = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $user->id,
            'name' => 'Before Cafe',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('cafes.update', $cafe), [
                'name' => 'After Cafe',
                'address' => '福岡市中央区',
                'nearest_station' => '天神駅',
                'memo' => '更新後メモ',
            ]);

        $response->assertRedirect(route('cafes.show', $cafe));

        $this->assertDatabaseHas('cafes', [
            'id' => $cafe->id,
            'user_id' => $user->id,
            'name' => 'After Cafe',
            'address' => '福岡市中央区',
            'nearest_station' => '天神駅',
            'memo' => '更新後メモ',
        ]);
    }

    public function test_user_cannot_update_other_users_cafe(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $otherUser->id,
            'name' => 'Other User Cafe',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('cafes.update', $cafe), [
                'name' => 'Updated By Wrong User',
                'address' => '不正な住所',
                'nearest_station' => '不正な駅',
                'memo' => '不正更新',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('cafes', [
            'id' => $cafe->id,
            'user_id' => $otherUser->id,
            'name' => 'Other User Cafe',
        ]);
    }

    public function test_authenticated_user_can_delete_own_cafe(): void
    {
        $user = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('cafes.destroy', $cafe));

        $response->assertRedirect(route('cafes.index'));

        $this->assertDatabaseMissing('cafes', [
            'id' => $cafe->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_cafe(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $cafe = Cafe::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('cafes.destroy', $cafe));

        $response->assertForbidden();

        $this->assertDatabaseHas('cafes', [
            'id' => $cafe->id,
            'user_id' => $otherUser->id,
        ]);
    }
}
