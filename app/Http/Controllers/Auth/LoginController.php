<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Show the login form.
     *
     * If the user is already authenticated, redirect them
     * to the catalog instead of showing the form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('catalog.index');
        }

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Process an authentication attempt.
     *
     * Validates the incoming request, checks the provided
     * credentials, and logs the user in if successful.
     * The session is regenerated to protect against session fixation.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // Validate the request data.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        // Attempt to authenticate as a regular user
        if (Auth::guard('web')->attempt(array_merge($credentials, ['is_blocked' => false]), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('catalog.index'));
        }

        // If login failed, check if it was because the user is blocked.
        if (Auth::guard('web')->validate($credentials)) {
            return back()->withErrors([
                'blocked' => 'A sua conta encontra-se bloqueada.',
            ])->onlyInput('email');
        }

        // Else, attempt to authenticate as an admin.
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
 
        // Authentication failed: return back with an error message.
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
