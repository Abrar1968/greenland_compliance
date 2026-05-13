<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SocialLink;

class SiteController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::firstOrCreate([]);

        return response()->json([
            'data' => [
                'site_name' => $settings->site_name,
                'meta_title' => $settings->meta_title,
                'meta_description' => $settings->meta_description,
                'logo_url' => $settings->logo_url,
                'primary_phone' => $settings->primary_phone,
                'primary_email' => $settings->primary_email,
                'address' => $settings->address,
                'business_hours' => $settings->business_hours,
                'footer_description' => $settings->footer_description,
                'footer_cta_title' => $settings->footer_cta_title,
                'footer_cta_text' => $settings->footer_cta_text,
                'footer_cta_button_label' => $settings->footer_cta_button_label,
                'footer_cta_button_href' => $settings->footer_cta_button_href ?? '/contact',
                'copyright_text' => $settings->copyright_text,
                'company_presentation_url' => $settings->company_presentation_url,
                'how_we_work_video_url' => $settings->how_we_work_video_url,
                'map_embed_url' => $settings->map_embed_url,
                'office_image_url' => $settings->office_image_url,
                'social_links' => SocialLink::where('is_active', true)->orderBy('sort_order')->get(['id', 'platform', 'url']),
            ],
        ]);
    }
}
