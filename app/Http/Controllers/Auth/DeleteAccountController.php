<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DeleteAccountController extends Controller
{
    /**
     * Handle the deletion of the user account.
     *
     * This method:
     * - Removes the user's profile image from storage.
     * - Anonymizes the username and email.
     * - Invalidates the password hash.
     * - Sets is_active to false.
     * - Destroys all active sessions for the user.
     */
    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->profile_img_url) {
            Storage::disk('public')->delete($user->profile_img_url);
            $user->profile_img_url = null;
        }

        $userId = $user->getKey();
        $timestamp = now()->timestamp;
        $user->username = "deleted-{$userId}-{$timestamp}";
        $user->email = "deleted-{$userId}-{$timestamp}@liberato.com";
        $user->password_hash = Str::random(60);
        $user->is_active = false;

        $user->save();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'A tua conta foi eliminada com sucesso.');
    }
}