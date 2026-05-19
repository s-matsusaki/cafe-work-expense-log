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
}
