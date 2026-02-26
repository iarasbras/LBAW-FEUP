<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Show the user registration form.
     */
    public function showRegistrationForm(): View
    {
        // Render the registration view.
        return view('auth.register');
    }

    /**
     * Handle a new user registration request.
     *
     * This method:
     * - Validates the registration input data.
     * - Creates a new user with a hashed password.
     * - Logs the user in automatically after registration.
     * - Regenerates the session to prevent fixation attacks.
     * - Redirects the user to o catálogo com uma mensagem de boas-vindas.
     */
    public function register(Request $request)
    {
        // Validate registration input.
        $request->validate([
            'username' => 'required|string|max:250|unique:users,username',
            'email' => 'required|email|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the new user.
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
        ]);

        // Attempt login for the newly registered user.
        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);

        // Regenerate session for security (protection against session fixation).
        $request->session()->regenerate();

        // Redirect to catalog page with a success message.
        return redirect()->route('catalog.index')
            ->withSuccess('Conta criada com sucesso! Bem-vindo à Liberato.');
    }
}
