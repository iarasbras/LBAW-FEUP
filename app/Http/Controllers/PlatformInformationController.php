<?php

namespace App\Http\Controllers;

use App\Models\PlatformInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlatformInformationController extends Controller
{
    public function about()
    {
        $info = PlatformInformation::whereIn('name', [
            'about_us_title', 'about_us_long', 'support_email', 'support_phone'
        ])->pluck('value', 'name');

        return view('pages.about_us', compact('info'));
    }

    public function showEditForm()
    {
        if (!Auth::guard('admin')->check()) {
            abort(403,'Unauthorized action.');
        }
        $info = PlatformInformation::whereIn('name', [
            'about_us_title', 'about_us_long', 'support_email', 'support_phone'
        ])->pluck('value', 'name');

        return view('admin.about_us_edit', compact('info'));
    }

    public function home()
    {
        $info = PlatformInformation::whereIn('name', [
            'site_name', 'about_us'
        ])->pluck('value','name');

        return view('home', compact('info'));
    }

    public function edit(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            abort(403, 'Unauthorized action.');
        }

        // Filter data
        $data = $request->except('_token');

        foreach ($data as $name => $value) {
            // Sanitize data (trim whitespace and strip HTML tags)
            $sanitizedValue = strip_tags(trim((string) $value));
            PlatformInformation::updateOrCreate(['name' => $name], ['value' => $sanitizedValue]);
        }
        return back()->withSuccess('Informação de sistema atualizada com sucesso.');
    }

    public static function getValue(string $name)
    {
        return PlatformInformation::where('name', $name)->value('value');
    }
}