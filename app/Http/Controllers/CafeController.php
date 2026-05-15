<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use Illuminate\Http\Request;

class CafeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cafes = Cafe::latest()->get();

        return view('cafes.index',compact('cafes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cafes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'nearest_station' => ['nullable', 'string', 'max:255'],
            'memo' => ['nullable', 'string'],
        ]);

        // あとで以下に変更
        // $validated['user_id] = $auth->id();
        $validated['user_id'] = 1;

        Cafe::create($validated);

        return redirect()
            ->route('cafes.index')
            ->with('status', 'カフェを登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cafe $cafe)
    {
        return view('cafes.show', compact('cafe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cafe $cafe)
    {
        return view('cafes.edit', compact('cafe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cafe $cafe)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'nearest_station' => ['nullable', 'string', 'max:255'],
            'memo' => ['nullable', 'string'],
        ]);

        $cafe->update($validated);

        return redirect()
            ->route('cafes.show', $cafe)
            ->with('status', 'カフェ情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cafe $cafe)
    {
        $cafe->delete();

        return redirect()
            ->route('cafes.index')
            ->with('status', 'カフェを削除しました。');
    }
}
