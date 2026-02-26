<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's profile page.
     */
    public function show(): View
    {
        if (Auth::user()->is_blocked) {
            abort(403, 'A sua conta encontra-se bloqueada.');
        }        

        return view('pages.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update profile information and optional avatar.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        
        if ($user->is_blocked) {
            abort(403, 'A sua conta encontra-se bloqueada.');
        }

        $validated = $request->validate([
            'username' => 'required|string|max:250|unique:users,username,' . $user->getKey() . ',user_id',
            'email' => 'required|email|max:250|unique:users,email,' . $user->getKey() . ',user_id',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_img' => 'nullable|image|max:2048',
        ]);

        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password_hash = Hash::make($validated['password']);
        }

        if ($request->hasFile('profile_img')) {
            if ($user->profile_img_url) {
                Storage::disk('public')->delete($user->profile_img_url);
            }

            $path = $request->file('profile_img')->store('profiles', 'public');
            $user->profile_img_url = $path;
        } elseif ($request->boolean('remove_profile_img')) {
            if ($user->profile_img_url) {
                Storage::disk('public')->delete($user->profile_img_url);
            }
            $user->profile_img_url = null;
        }

        $user->save();

        return back()->withSuccess('Perfil atualizado com sucesso.');
    }
}

