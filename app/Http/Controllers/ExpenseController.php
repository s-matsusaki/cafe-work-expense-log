<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\Expense;
use App\Models\WorkSession;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['cafe', 'workSession']);

        if ($request->filled('accounting_recorded')) {
            $query->where('accounting_recorded', $request->boolean('accounting_recorded'));
        }

        if ($request->filled('expense_month')) {
            $query->whereYear('expense_date', substr($request->expense_month, 0, 4))
            ->whereMonth('expense_date', substr($request->expense_month, 5, 2));
        }

        if ($request->filled('expense_type')) {
            $query->where('expense_type', $request->expense_type);
        }
        
        $expenses = $query
            ->latest('expense_date')
            ->latest()
            ->get();
        
        $totalAmount = $expenses->sum('amount');
        $unrecordedCount = $expenses->where('accounting_recorded', false)->count();
        $recordedCount = $expenses->where('accounting_recorded', true)->count();

        return view('expenses.index', compact(
            'expenses',
            'totalAmount',
            'unrecordedCount',
            'recordedCount',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cafes = Cafe::orderBy('name')->get();

        $workSessions = WorkSession::with('cafe')
            ->latest('work_date')
            ->latest()
            ->get();
        
        return view('expenses.create', compact('cafes', 'workSessions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = 1;
        $validated['acounting_recorded'] = $request->boolean('acounting_recorded');

        Expense::create($validated);

        return redirect()
            ->route('expenses.index')
            ->with('status', '支出を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['cafe', 'workSession']);

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $cafes = Cafe::orderBy('name')->get();

        $workSessions = WorkSession::with('cafe')
            ->latest('work_date')
            ->latest()
            ->get();

        return view('expenses.edit', compact('expense', 'cafes', 'workSessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $validated = $request->validated();

        // accounting_recorded が送られてきていればtrue 送られてきていなければ false として扱う
        $validated['accounting_recorded'] = $request->boolean('accounting_recorded');

        $expense->update($validated);

        return redirect()
            ->route('expenses.show', $expense)
            ->with('status', '支出を更新しました');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('status', '支出を削除しました');
    }
}
