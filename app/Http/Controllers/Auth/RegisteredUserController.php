<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\RateLimiter;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        abort_unless(config('features.allow_user_registration'), 404);

        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(config('features.allow_user_registration'), 404);

        $throttleKey = 'register|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            abort(429, "登録試行回数が多すぎます。{$seconds}秒後に再試行してください。");
        }

        RateLimiter::hit($throttleKey, 1800);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
