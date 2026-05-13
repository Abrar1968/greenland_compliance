<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use App\Models\Achievement;
use App\Models\ApproachCard;
use App\Models\Faq;
use App\Models\MissionBullet;
use App\Models\Partner;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineMilestone;

class AboutController extends Controller
{
    public function index()
    {
        $about = AboutSetting::firstOrCreate([]);
        $site = SiteSetting::firstOrCreate([]);

        return response()->json([
            'data' => [
                'banner_label' => $about->banner_label,
                'company_presentation_url' => $site->company_presentation_url,
                'hero' => [
                    'heading_line1' => $about->hero_heading_line1,
                    'heading_line2' => $about->hero_heading_line2,
                    'paragraph' => $about->hero_paragraph,
                    'image_url' => $about->hero_image_url,
                    'cta_label' => $about->hero_cta_label,
                    'cta_href' => $about->hero_cta_href ?? '/contact',
                ],
                'overview' => [
                    'paragraph1' => $about->overview_paragraph1,
                    'paragraph2' => $about->overview_paragraph2,
                    'callout' => $about->overview_callout,
                    'mission_heading' => $about->mission_heading,
                    'mission_intro' => $about->mission_intro,
                    'mission_bullets' => MissionBullet::where('is_active', true)->orderBy('sort_order')->get(['id', 'text', 'sort_order']),
                    'how_we_work_video_url' => $site->how_we_work_video_url,
                    'timeline' => TimelineMilestone::orderBy('sort_order')->get(['id', 'year', 'title', 'description', 'sort_order']),
                ],
                'approach' => [
                    'intro1' => $about->approach_intro1,
                    'intro2' => $about->approach_intro2,
                    'cards' => ApproachCard::where('is_active', true)->orderBy('sort_order')->get(['id', 'title', 'icon', 'description', 'sort_order']),
                ],
                'achievements' => Achievement::where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => [
                    'id' => $item->id, 'title' => $item->title, 'image_url' => $item->image_url, 'sort_order' => $item->sort_order,
                ]),
                'partners' => Partner::where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => [
                    'id' => $item->id, 'name' => $item->name, 'industry' => $item->industry, 'location' => $item->location, 'description' => $item->description, 'logo_url' => $item->logo_url, 'sort_order' => $item->sort_order,
                ]),
                'team' => TeamMember::where('is_active', true)->orderBy('sort_order')->get()->map(fn ($item) => [
                    'id' => $item->id, 'name' => $item->name, 'role' => $item->role, 'description' => $item->description, 'image_url' => $item->image_url, 'profile_slug' => $item->profile_slug, 'sort_order' => $item->sort_order,
                ]),
                'faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get(['id', 'question', 'answer', 'sort_order']),
                'testimonials' => Testimonial::where('is_active', true)->whereIn('page', ['about', 'global'])->orderBy('sort_order')->get()->map(fn ($item) => [
                    'id' => $item->id, 'author' => $item->author, 'role' => $item->role, 'quote' => $item->quote, 'avatar_url' => $item->avatar_url, 'sort_order' => $item->sort_order,
                ]),
                'footer_cta' => [
                    'text' => $about->footer_cta_text,
                    'button_label' => $about->footer_cta_button_label,
                    'button_href' => $about->footer_cta_button_href ?? '/contact',
                ],
            ],
        ]);
    }
}
