<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\MediaUploader;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.site', ['settings' => SiteSetting::firstOrCreate([])]);
    }

    public function update(Request $request, MediaUploader $uploader)
    {
        $settings = SiteSetting::firstOrCreate([]);
        $data = $request->validate([
            'site_name' => ['required', 'string'],
            'meta_title' => ['required', 'string'],
            'meta_description' => ['nullable', 'string'],
            'primary_phone' => ['nullable', 'string'],
            'primary_email' => ['nullable', 'email'],
            'address' => ['nullable', 'string'],
            'business_hours' => ['nullable', 'string'],
            'footer_description' => ['nullable', 'string'],
            'footer_cta_title' => ['nullable', 'string'],
            'footer_cta_text' => ['nullable', 'string'],
            'footer_cta_button_label' => ['nullable', 'string'],
            'footer_cta_button_href' => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
            'copyright_text' => ['nullable', 'string'],
            'how_we_work_video_url' => ['nullable', 'string'],
            'logo_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'office_image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'company_presentation_file' => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
        ]);

        foreach (['logo_path' => 'logo', 'office_image_path' => 'about'] as $field => $directory) {
            if ($request->hasFile($field)) {
                $data[$field] = $uploader->storeImage($request->file($field), $directory, $settings->{$field});
            }
        }
        if ($request->hasFile('company_presentation_file')) {
            $data['company_presentation_file'] = $uploader->storeFile($request->file('company_presentation_file'), 'resources', $settings->company_presentation_file);
        }

        $settings->update($data);

        return back()->with('status', 'Site settings updated.');
    }
}
