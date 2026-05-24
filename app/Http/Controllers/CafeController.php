<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use Illuminate\Http\Request;
use App\Http\Requests\CafeRequest;

class CafeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cafes = Cafe::where('user_id', auth()->id())
        ->latest()->get();

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
    public function store(CafeRequest $request)
    {
        $validated = $request->validated();

        // あとで以下に変更
        $validated['user_id'] = auth()->id();

        Cafe::create($validated);

        return redirect()
            ->route('cafes.index')
            ->with('status', '場所を登録しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cafe $cafe)
    {
        $this->authorize('view', $cafe);

        return view('cafes.show', compact('cafe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cafe $cafe)
    {
        $this->authorize('update', $cafe);

        return view('cafes.edit', compact('cafe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CafeRequest $request, Cafe $cafe)
    {
        $this->authorize('update', $cafe);

        $validated = $request->validated();

        $cafe->update($validated);

        return redirect()
            ->route('cafes.show', $cafe)
            ->with('status', '場所情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cafe $cafe)
    {
        $this->authorize('delete', $cafe);

        $cafe->delete();

        return redirect()
            ->route('cafes.index')
            ->with('status', '場所を削除しました。');
    }
}
