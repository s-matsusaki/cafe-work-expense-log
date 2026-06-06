<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonthlyReportRequest;
use App\Models\Expense;
use App\Models\WorkSession;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class MonthlyReportApiController extends Controller
{
    public function show(MonthlyReportRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $month = $validated['month'];

        $start = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfMonth();
        $end = Carbon::createFromFormat('Y-m-d', $month . '-01')->endOfMonth();

        $userId = $request->user()->id;

        $totalWorkMinutes = WorkSession::query()
            ->where('user_id', $userId)
            ->whereBetween('work_date', [$start, $end])
            ->sum('work_minutes');

        $totalExpenseAmount = Expense::query()
            ->where('user_id', $userId)
            ->whereBetween('expense_date', [$start, $end])
            ->sum('amount');

        $unrecordedExpenseCount = Expense::query()
            ->where('user_id', $userId)
            ->whereBetween('expense_date', [$start, $end])
            ->where('accounting_recorded', false)
            ->count();

        return response()->json([
            'month' => $month,
            'summary' => [
                'total_work_minutes' => $totalWorkMinutes,
                'total_work_hours' => round($totalWorkMinutes / 60, 1),
                'total_expense_amount' => $totalExpenseAmount,
                'unrecorded_expense_count' => $unrecordedExpenseCount,
            ],
        ]);
    }
}