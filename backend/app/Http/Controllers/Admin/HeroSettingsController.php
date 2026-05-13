<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use Illuminate\Http\Request;

class HeroSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings.hero', ['settings' => HeroSetting::firstOrCreate([])]);
    }

    public function update(Request $request)
    {
        HeroSetting::firstOrCreate([])->update($request->validate([
            'headline_line1' => ['required', 'string'],
            'headline_line2' => ['required', 'string'],
            'paragraph' => ['nullable', 'string'],
            'cta1_label' => ['required', 'string'],
            'cta1_href' => ['required', 'string'],
            'cta2_label' => ['required', 'string'],
            'cta2_href' => ['required', 'string'],
        ]));

        return back()->with('status', 'Hero settings updated.');
    }
}
