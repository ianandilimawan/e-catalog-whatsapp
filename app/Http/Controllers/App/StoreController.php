<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function edit()
    {
        $store = auth()->user()->store;
        if (!$store) {
            return redirect()->route('onboarding.store');
        }

        return view('app.store.edit', compact('store'));
    }

    public function update(Request $request)
    {
        $store = auth()->user()->store;
        if (!$store) {
            return redirect()->route('onboarding.store');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'wa_number' => 'required|string|max:20',
            'welcome_message' => 'nullable|string|max:1000',
            'theme_color' => 'nullable|string|max:20',
            'button_rounded' => 'nullable|boolean',
            'dark_mode' => 'nullable|boolean',
            'logo' => 'nullable|image|max:5120',
            'banner' => 'nullable|image|max:5120',

            // Tracking IDs — regex validation to allow ONLY IDs, preventing script injection
            'google_analytics_id' => ['nullable', 'string', 'max:30', 'regex:/^G-[A-Z0-9]{4,20}$/i'],
            'meta_pixel_id' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]{10,20}$/'],
            'google_search_console_code' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z0-9_\-]{10,80}$/'],

            // SEO overrides
            'seo_title' => 'nullable|string|max:120',
            'seo_description' => 'nullable|string|max:300',

            // Custom CTA Button Text
            'cta_button_text' => 'nullable|string|max:50',
        ]);

        // Sanitize SEO & CTA text fields
        if (isset($validated['seo_title'])) {
            $validated['seo_title'] = strip_tags($validated['seo_title']);
        }
        if (isset($validated['seo_description'])) {
            $validated['seo_description'] = strip_tags($validated['seo_description']);
        }
        if (isset($validated['cta_button_text'])) {
            $validated['cta_button_text'] = strip_tags($validated['cta_button_text']);
        }

        $uploader = new FileUploadService();

        if ($request->hasFile('logo')) {
            $logoPath = $uploader->folder('stores/logo')->upload($request->file('logo'), $store->logo);
            $validated['logo'] = $logoPath;
        }

        if ($request->hasFile('banner')) {
            $bannerPath = $uploader->folder('stores/banner')->upload($request->file('banner'), $store->banner);
            $validated['banner'] = $bannerPath;
        }

        $validated['button_rounded'] = $request->has('button_rounded');
        $validated['dark_mode'] = $request->has('dark_mode');

        $store->update($validated);

        return redirect()->route('app.store.edit')->with('success', 'Pengaturan toko berhasil disimpan!');
    }
}
