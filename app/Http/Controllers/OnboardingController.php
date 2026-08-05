<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    public function show()
    {
        if (auth()->user()->store) {
            $user = auth()->user();
            if ($user->hasRole('admin-toko') && !$user->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
                return redirect()->route('app.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }
        $settings = Setting::getSettings();
        return view('onboarding.store', compact('settings'));
    }

    public function store(Request $request)
    {
        // If user somehow already has a store, redirect them away
        if (auth()->user()->store) {
            $user = auth()->user();
            if ($user->hasRole('admin-toko') && !$user->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
                return redirect()->route('app.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'wa_number' => ['required', 'string', 'min:10', 'regex:/^[0-9]+$/'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:stores'],
        ]);

        Store::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'wa_number' => $request->wa_number,
            'theme_color' => '#10b981', // Default emerald theme
            'button_rounded' => true, // Default button style (true means rounded-full)
            'dark_mode' => false,
            'welcome_message' => 'Selamat datang di ' . $request->name,
        ]);

        $user = auth()->user();
        if ($user->hasRole('admin-toko') && !$user->hasAnyRole(['administrator', 'admin', 'super-admin'])) {
            return redirect()->route('app.dashboard')
                ->with('success', 'Toko kamu siap! Mulai tambah produk pertama.');
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Toko kamu siap! Mulai tambah produk pertama.');
    }
}
