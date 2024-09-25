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
            'email' => 'required|string|email', // Ensures the email format is valid
            'password' => 'required|string',
        ]);
    
        // Attempt login using email
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
    
            return response()->json(['success' => true, 'message' => 'Login successful.']);
        }
    
        // If login fails, return an error response
        return response()->json(['success' => false, 'message' => 'The provided credentials do not match our records.'], 401);
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
