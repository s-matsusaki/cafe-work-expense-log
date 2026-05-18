<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSession;
use App\Models\Cafe;
use App\Http\Requests\WorkSessionRequest;

class WorkSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WorkSession::with('cafe');

        if ($request->filled('work_month')) {
            $query->whereYear('work_date', substr($request->work_month, 0, 4))
                ->whereMonth('work_date', substr($request->work_month, 5, 2));
        }

        if ($request->filled('cafe_id')) {
            $query->where('cafe_id', $request->cafe_id);
        }

        if($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $workSessions = $query
            ->latest('work_date')
            ->latest()
            ->get();

        $cafes = Cafe::orderBy('name')->get();

        $categories = WorkSession::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $totalWorkMinutes = $workSessions->sum('work_minutes');

        return view('work-sessions.index', compact(
            'workSessions',
            'cafes',
            'categories',
            'totalWorkMinutes',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cafes = Cafe::orderBy('name')->get();

        return view('work-sessions.create', compact('cafes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkSessionRequest $request)
    {
        $validated = $request->validated();

        // TODO:あとでいかに置換
        // $validated['user_id'] = auth()->id();
        $validated['user_id'] = 1;

        WorkSession::create($validated);

        return redirect()
            ->route('work-sessions.index')
            ->with('status', '作業記録を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkSession $workSession)
    {
        $workSession->load('cafe');

        return view('work-sessions.show', compact('workSession'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkSession $workSession)
    {
        $cafes = Cafe::orderBy('name')->get();

        return view('work-sessions.edit', compact('workSession', 'cafes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkSessionRequest $request, WorkSession $workSession)
    {
        $validated = $request->validated();

        $workSession->update($validated);

        return redirect()
            ->route('work-sessions.show', $workSession)
            ->with('status', '作業記録を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSession $workSession)
    {
        $workSession->delete();

        return redirect()
            ->route('work-sessions.index')
            ->with('status', '作業記録を削除しました。');
    }
}
