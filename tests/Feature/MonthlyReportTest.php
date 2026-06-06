<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use App\Models\WorkSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_monthly_report_page(): void
    {
        $response = $this->get(route('reports.monthly'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_monthly_report_page(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('reports.monthly'));

        $response->assertOk();
        $response->assertSee('月次分析レポート');
        $response->assertSee('monthly-report-app');
    }

    public function test_monthly_report_api_returns_only_authenticated_users_selected_month_summary(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        WorkSession::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-10',
            'work_minutes' => 120,
        ]);

        WorkSession::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-05-20',
            'work_minutes' => 60,
        ]);

        WorkSession::factory()->create([
            'user_id' => $user->id,
            'work_date' => '2026-06-01',
            'work_minutes' => 999,
        ]);

        WorkSession::factory()->create([
            'user_id' => $otherUser->id,
            'work_date' => '2026-05-15',
            'work_minutes' => 999,
        ]);

        Expense::factory()->create([
            'user_id' => $user->id,
            'expense_date' => '2026-05-10',
            'amount' => 1000,
            'accounting_recorded' => false,
        ]);

        Expense::factory()->create([
            'user_id' => $user->id,
            'expense_date' => '2026-05-20',
            'amount' => 2500,
            'accounting_recorded' => true,
        ]);

        Expense::factory()->create([
            'user_id' => $user->id,
            'expense_date' => '2026-06-01',
            'amount' => 9999,
            'accounting_recorded' => false,
        ]);

        Expense::factory()->create([
            'user_id' => $otherUser->id,
            'expense_date' => '2026-05-15',
            'amount' => 9999,
            'accounting_recorded' => false,
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson(route('api.reports.monthly', ['month' => '2026-05']));

        $response->assertOk();
        $response->assertJsonPath('month', '2026-05');
        $response->assertJsonPath('summary.total_work_minutes', 180);
        $response->assertJsonPath('summary.total_work_hours', 3);
        $response->assertJsonPath('summary.total_expense_amount', 3500);
        $response->assertJsonPath('summary.unrecorded_expense_count', 1);
    }

    public function test_monthly_report_api_validates_month_format(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->getJson(route('api.reports.monthly', ['month' => '2026-13']));

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('month');
    }
}
