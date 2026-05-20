<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Cafe;
use App\Models\Expense;
use App\Models\User;
use App\Models\WorkSession;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'demo@example.com')->firstOrFail();

        $cafeA = Cafe::updateOrCreate(
            [
                'user_id' => $user->id,
                'name' => 'Demo Cafe 博多',
            ],
            [
                'address' => '福岡市博多区',
                'nearest_station' => '博多駅',
                'memo' => '駅近で作業しやすいカフェ。電源あり。',
            ]
        );

        $cafeB = Cafe::updateOrCreate(
            [
                'user_id' => $user->id,
                'name' => 'Demo Coffee 天神',
            ],
            [
                'address' => '福岡市中央区',
                'nearest_station' => '天神駅',
                'memo' => '午前中は静かで集中しやすい。',
            ]
        );

        $bookA = Book::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Laravel実践開発',
            ],
            [
                'purchased_on' => now()->subDays(10)->toDateString(),
                'status' => 'reading',
                'memo' => 'FormRequest、Policy、Feature Test周りを重点的に読む。',
            ]
        );

        $bookB = Book::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Docker入門',
            ],
            [
                'purchased_on' => now()->subDays(20)->toDateString(),
                'status' => 'done',
                'memo' => 'Docker Composeとコンテナ間通信の復習用。',
            ]
        );

        $workSessionA = WorkSession::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'カフェCRUD実装',
                'work_date' => now()->subDays(5)->toDateString(),
            ],
            [
                'cafe_id' => $cafeA->id,
                'work_minutes' => 120,
                'category' => 'development',
                'memo' => 'CafeController、Blade、Policy、Feature Testを実装。',
            ]
        );

        $workSessionB = WorkSession::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => '支出管理の集計機能実装',
                'work_date' => now()->subDays(3)->toDateString(),
            ],
            [
                'cafe_id' => $cafeB->id,
                'work_minutes' => 150,
                'category' => 'development',
                'memo' => '支出月・支出種別・会計記録済みの絞り込みを実装。',
            ]
        );

        WorkSession::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'README整理',
                'work_date' => now()->subDay()->toDateString(),
            ],
            [
                'cafe_id' => null,
                'work_minutes' => 60,
                'category' => 'documentation',
                'memo' => '開発目的、使用技術、環境構築手順、テスト手順を整理。',
            ]
        );

        Expense::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'コーヒー代',
                'expense_date' => now()->subDays(5)->toDateString(),
            ],
            [
                'amount' => 680,
                'expense_type' => 'cafe',
                'payment_method' => 'card',
                'cafe_id' => $cafeA->id,
                'work_session_id' => $workSessionA->id,
                'book_id' => null,
                'accounting_recorded' => true,
                'accounting_recorded_at' => now()->subDays(4),
                'accounting_memo' => '会計ソフトに登録済み。',
                'memo' => '作業時のカフェ代。',
            ]
        );

        Expense::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => '軽食代',
                'expense_date' => now()->subDays(3)->toDateString(),
            ],
            [
                'amount' => 520,
                'expense_type' => 'cafe',
                'payment_method' => 'cash',
                'cafe_id' => $cafeB->id,
                'work_session_id' => $workSessionB->id,
                'book_id' => null,
                'accounting_recorded' => false,
                'accounting_recorded_at' => null,
                'accounting_memo' => null,
                'memo' => '午後作業中の軽食。',
            ]
        );

        Expense::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Laravel本購入',
                'expense_date' => now()->subDays(10)->toDateString(),
            ],
            [
                'amount' => 3200,
                'expense_type' => 'book',
                'payment_method' => 'card',
                'cafe_id' => null,
                'work_session_id' => null,
                'book_id' => $bookA->id,
                'accounting_recorded' => false,
                'accounting_recorded_at' => null,
                'accounting_memo' => null,
                'memo' => '学習用書籍として購入。',
            ]
        );

        Expense::updateOrCreate(
            [
                'user_id' => $user->id,
                'title' => 'Docker本購入',
                'expense_date' => now()->subDays(20)->toDateString(),
            ],
            [
                'amount' => 2800,
                'expense_type' => 'book',
                'payment_method' => 'card',
                'cafe_id' => null,
                'work_session_id' => null,
                'book_id' => $bookB->id,
                'accounting_recorded' => true,
                'accounting_recorded_at' => now()->subDays(18),
                'accounting_memo' => '会計ソフトに登録済み。',
                'memo' => 'Docker学習用。',
            ]
        );
    }
}