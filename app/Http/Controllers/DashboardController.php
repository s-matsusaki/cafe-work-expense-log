<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\WorkSession;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        $monthlyWorkMinutes = WorkSession::query()
            ->whereYear('work_date', Carbon::now()->year)
            ->whereMonth('work_date', Carbon::now()->month)
            ->sum('work_minutes');

        $monthlyExpenseAmount = Expense::query()
            ->whereYear('expense_date', Carbon::now()->year)
            ->whereMonth('expense_date', Carbon::now()->month)
            ->sum('amount');

        $unrecordedExpenseCount = Expense::query()
            ->where('accounting_recorded', false)
            ->count();

        $recentWorkSessions = WorkSession::with('cafe')
            ->latest('work_date')
            ->latest()
            ->limit(5)
            ->get();

        $recentExpenses = Expense::with(['cafe', 'workSession'])
            ->latest('expense_date')
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
        ));
    }
}
