<?php

namespace App\Support;

use App\Models\Achievement;
use App\Models\ApproachCard;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use App\Models\ContactDepartment;
use App\Models\Faq;
use App\Models\FormTemplate;
use App\Models\HeroSlide;
use App\Models\MissionBullet;
use App\Models\NavItem;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Publication;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineMilestone;

class AdminResources
{
    public static function get(string $key): array
    {
        return self::all()[$key] ?? throw new \InvalidArgumentException("Unknown admin resource [$key].");
    }

    public static function all(): array
    {
        return [
            'hero-slides' => ['title' => 'Hero Slides', 'model' => HeroSlide::class, 'route' => 'hero-slides', 'fields' => ['alt_text', 'sort_order', 'is_active'], 'images' => ['image_path' => 'hero'], 'required_images' => ['image_path']],
            'nav-items' => ['title' => 'Navigation Items', 'model' => NavItem::class, 'route' => 'nav-items', 'fields' => ['label', 'href', 'location', 'sort_order', 'is_active']],
            'social-links' => ['title' => 'Social Links', 'model' => SocialLink::class, 'route' => 'social-links', 'fields' => ['platform', 'url', 'sort_order', 'is_active']],
            'service-categories' => ['title' => 'Service Categories', 'model' => ServiceCategory::class, 'route' => 'service-categories', 'fields' => ['label', 'slug', 'sort_order']],
            'services' => ['title' => 'Services', 'model' => Service::class, 'route' => 'services', 'fields' => ['service_category_id', 'title', 'price', 'description', 'badge', 'sort_order', 'is_active'], 'selects' => ['service_category_id' => [ServiceCategory::class, 'label']]],
            'case-study-categories' => ['title' => 'Case Study Categories', 'model' => CaseStudyCategory::class, 'route' => 'case-study-categories', 'fields' => ['name', 'slug', 'sort_order']],
            'case-studies' => ['title' => 'Case Studies', 'model' => CaseStudy::class, 'route' => 'case-studies', 'fields' => ['case_study_category_id', 'title', 'slug', 'summary', 'body', 'sort_order', 'is_active'], 'selects' => ['case_study_category_id' => [CaseStudyCategory::class, 'name']], 'images' => ['image_path' => 'case-studies']],
            'testimonials' => ['title' => 'Testimonials', 'model' => Testimonial::class, 'route' => 'testimonials', 'fields' => ['author', 'role', 'quote', 'page', 'sort_order', 'is_active'], 'images' => ['avatar_path' => 'about']],
            'timeline-milestones' => ['title' => 'Timeline Milestones', 'model' => TimelineMilestone::class, 'route' => 'timeline-milestones', 'fields' => ['year', 'title', 'description', 'sort_order']],
            'mission-bullets' => ['title' => 'Mission Bullets', 'model' => MissionBullet::class, 'route' => 'mission-bullets', 'fields' => ['text', 'sort_order', 'is_active']],
            'approach-cards' => ['title' => 'Approach Cards', 'model' => ApproachCard::class, 'route' => 'approach-cards', 'fields' => ['title', 'icon', 'description', 'sort_order', 'is_active']],
            'achievements' => ['title' => 'Achievements', 'model' => Achievement::class, 'route' => 'achievements', 'fields' => ['title', 'sort_order', 'is_active'], 'images' => ['image_path' => 'achievements']],
            'partners' => ['title' => 'Partners', 'model' => Partner::class, 'route' => 'partners', 'fields' => ['name', 'industry', 'location', 'description', 'sort_order', 'is_active'], 'images' => ['logo_path' => 'partners']],
            'team-members' => ['title' => 'Team Members', 'model' => TeamMember::class, 'route' => 'team-members', 'fields' => ['name', 'role', 'description', 'profile_slug', 'sort_order', 'is_active'], 'images' => ['image_path' => 'team']],
            'faqs' => ['title' => 'FAQs', 'model' => Faq::class, 'route' => 'faqs', 'fields' => ['question', 'answer', 'sort_order', 'is_active']],
            'contact-departments' => ['title' => 'Contact Departments', 'model' => ContactDepartment::class, 'route' => 'contact-departments', 'fields' => ['title', 'email', 'sort_order', 'is_active']],
            'publications' => ['title' => 'Publications', 'model' => Publication::class, 'route' => 'publications', 'fields' => ['title', 'format', 'category', 'sort_order', 'is_active'], 'files' => ['file_path' => 'resources']],
            'form-templates' => ['title' => 'Form Templates', 'model' => FormTemplate::class, 'route' => 'form-templates', 'fields' => ['title', 'category_group', 'format', 'language', 'sort_order', 'is_active'], 'files' => ['file_path' => 'resources']],
            'news-posts' => ['title' => 'News Posts', 'model' => NewsPost::class, 'route' => 'news-posts', 'fields' => ['title', 'category', 'published_at', 'format', 'body', 'external_url', 'sort_order', 'is_active'], 'images' => ['image_path' => 'news']],
            'pages' => ['title' => 'CMS Pages', 'model' => Page::class, 'route' => 'pages', 'fields' => ['title', 'slug', 'content', 'is_active']],
        ];
    }
}
