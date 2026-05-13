<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use App\Support\MediaUploader;
use Illuminate\Http\Request;

class AboutSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.about', ['settings' => AboutSetting::firstOrCreate([])]);
    }

    public function update(Request $request, MediaUploader $uploader)
    {
        $settings = AboutSetting::firstOrCreate([]);
        $data = $request->validate([
            'banner_label' => ['required', 'string'],
            'hero_heading_line1' => ['required', 'string'],
            'hero_heading_line2' => ['required', 'string'],
            'hero_paragraph' => ['nullable', 'string'],
            'hero_image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'hero_cta_label' => ['required', 'string'],
            'hero_cta_href' => ['nullable', 'string'],
            'overview_paragraph1' => ['nullable', 'string'],
            'overview_paragraph2' => ['nullable', 'string'],
            'overview_callout' => ['nullable', 'string'],
            'mission_heading' => ['required', 'string'],
            'mission_intro' => ['nullable', 'string'],
            'approach_intro1' => ['nullable', 'string'],
            'approach_intro2' => ['nullable', 'string'],
            'footer_cta_text' => ['nullable', 'string'],
            'footer_cta_button_label' => ['nullable', 'string'],
            'footer_cta_button_href' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('hero_image_path')) {
            $data['hero_image_path'] = $uploader->storeImage($request->file('hero_image_path'), 'about', $settings->hero_image_path);
        }

        $settings->update($data);

        return back()->with('status', 'About settings updated.');
    }
}
