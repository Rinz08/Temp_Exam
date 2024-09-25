<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string',
    ]);

    // Determine if login is an email or username
    $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Attempt login using either email or username
    if (Auth::attempt([$fieldType => $request->login, 'password' => $request->password], $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    // Return success JSON response for Vue.js
    return response()->json([
        'success' => true,
        'user' => Auth::user()
    ]);

    // Return failure JSON response
    return response()->json([
        'success' => false,
        'message' => 'The provided credentials do not match our records.'
    ], 401);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
