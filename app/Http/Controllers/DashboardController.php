<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\WorkSession;
use App\Models\Book;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        $monthlyWorkMinutes = WorkSession::query()
            ->where('user_id', auth()->id())
            ->whereYear('work_date', Carbon::now()->year)
            ->whereMonth('work_date', Carbon::now()->month)
            ->sum('work_minutes');

        $monthlyExpenseAmount = Expense::query()
            ->where('user_id', auth()->id())
            ->whereYear('expense_date', Carbon::now()->year)
            ->whereMonth('expense_date', Carbon::now()->month)
            ->sum('amount');

        $unrecordedExpenseCount = Expense::query()
            ->where('user_id', auth()->id())
            ->where('accounting_recorded', false)
            ->count();

        $recentWorkSessions = WorkSession::with('cafe')
            ->where('user_id', auth()->id())
            ->latest('work_date')
            ->latest()
            ->limit(5)
            ->get();

        $recentExpenses = Expense::with(['cafe', 'workSession'])
            ->where('user_id', auth()->id())
            ->latest('expense_date')
            ->latest()
            ->limit(5)
            ->get();

        $readingBookCount = Book::query()
            ->where('user_id', auth()->id())
            ->where('status', 'reading')
            ->count();

        $unreadBookCount = Book::query()
            ->where('user_id', auth()->id())
            ->where('status', 'unread')
            ->count();

        $recentBooks = Book::query()
            ->where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'currentMonth',
            'monthlyWorkMinutes',
            'monthlyExpenseAmount',
            'unrecordedExpenseCount',
            'recentWorkSessions',
            'recentExpenses',
            'readingBookCount',
            'unreadBookCount',
            'recentBooks',
        ));
    }
}
