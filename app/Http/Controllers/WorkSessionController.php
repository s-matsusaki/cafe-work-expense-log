<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSession;
use App\Models\Cafe;

class WorkSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workSessions = WorkSession::with('cafe')
            ->latest('work_date')
            ->latest()
            ->get();

        return view('work-sessions.index', compact('workSessions'));
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cafe_id' => ['nullable', 'exists:cafes,id'],
            'work_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'work_minutes' => ['nullable', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
            'memo' => ['nullable', 'string'],
        ]);

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
    public function update(Request $request, WorkSession $workSession)
    {
        $validated = $request->validate([
            'cafe_id' => ['nullable', 'exists:cafes,id'],
            'work_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'work_minutes' => ['nullable', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:50'],
            'memo' => ['nullable', 'string'],
        ]);

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
