<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use App\Models\HeroSlide;

class HeroController extends Controller
{
    public function index()
    {
        $settings = HeroSetting::firstOrCreate([]);

        return response()->json([
            'data' => [
                'headline_line1' => $settings->headline_line1,
                'headline_line2' => $settings->headline_line2,
                'paragraph' => $settings->paragraph,
                'cta1_label' => $settings->cta1_label,
                'cta1_href' => $settings->cta1_href,
                'cta2_label' => $settings->cta2_label,
                'cta2_href' => $settings->cta2_href,
                'slides' => HeroSlide::where('is_active', true)->orderBy('sort_order')->get()
                    ->map(fn ($slide) => [
                        'id' => $slide->id,
                        'image_url' => $slide->image_url,
                        'alt_text' => $slide->alt_text,
                        'sort_order' => $slide->sort_order,
                    ]),
            ],
        ]);
    }
}
