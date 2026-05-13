<?php

namespace Database\Seeders;

use App\Models\AboutSetting;
use App\Models\Admin;
use App\Models\Achievement;
use App\Models\ApproachCard;
use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use App\Models\ContactDepartment;
use App\Models\Faq;
use App\Models\FormTemplate;
use App\Models\HeroSetting;
use App\Models\HeroSlide;
use App\Models\MissionBullet;
use App\Models\NavItem;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Publication;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\TimelineMilestone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@greenlandcompliance.com')],
            ['name' => 'Greenland Admin', 'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@1234'))]
        );

        SiteSetting::updateOrCreate(['id' => 1], [
            'site_name' => 'Greenland Business & Compliance',
            'meta_title' => 'Greenland Business & Compliance',
            'meta_description' => 'Professional Advisory, Accounting, and Regulatory solutions in Bangladesh',
            'logo_path' => 'logo/gc.png',
            'primary_phone' => '+8801987-644603',
            'primary_email' => 'contact@greenlandcompliance.com',
            'address' => 'Bottola Bazar, Bhakurta, Savar, Dhaka-1313, Bangladesh.',
            'business_hours' => 'Mon to Sat 8 am to 10 pm | Sunday CLOSED',
            'footer_description' => 'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. Building sustainable business foundations in Bangladesh since 1985.',
            'footer_cta_title' => 'Ready to take your business to the next level?',
            'footer_cta_text' => 'Our expert consultants are ready to help you navigate the complexities of compliance and growth in Bangladesh.',
            'footer_cta_button_label' => 'Request a Free Quote',
            'footer_cta_button_href' => '/contact',
            'copyright_text' => '© 2026 Greenland Business & Compliance. All Rights Reserved.',
            'company_presentation_file' => null,
            'how_we_work_video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!',
        ]);

        foreach ([['linkedin', 'https://linkedin.com/company/greenland'], ['facebook', 'https://facebook.com/greenland'], ['twitter', 'https://twitter.com/greenland'], ['instagram', 'https://instagram.com/greenland'], ['youtube', 'https://youtube.com/@greenland']] as $i => [$platform, $url]) {
            SocialLink::updateOrCreate(['platform' => $platform], ['url' => $url, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        $navs = [
            ['Home', '/', 'header'], ['Services', '/services', 'header'], ['Case Studies', '/case-studies', 'header'], ['About Us', '/about', 'header'], ['Contact US', '/contact', 'header'], ['Resources', '/resources', 'header'],
            ['Home', '/', 'footer_quick'], ['About Us', '/about', 'footer_quick'], ['Our Services', '/services', 'footer_quick'], ['Case Studies', '/case-studies', 'footer_quick'], ['Resources', '/resources', 'footer_quick'], ['Contact Us', '/contact', 'footer_quick'],
            ['Business Advisory', '/services', 'footer_services'], ['Audit & Assurance', '/services', 'footer_services'], ['Taxation Services', '/services', 'footer_services'], ['Regulatory Compliance', '/services', 'footer_services'], ['Human Capital Management', '/services', 'footer_services'], ['Strategy Consulting', '/services', 'footer_services'],
            ['Privacy Policy', '/privacy', 'footer_policy'], ['Terms of Service', '/terms', 'footer_policy'], ['Cookie Settings', '/cookies', 'footer_policy'],
        ];
        foreach ($navs as $i => [$label, $href, $location]) {
            NavItem::updateOrCreate(['label' => $label, 'location' => $location], ['href' => $href, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        HeroSetting::updateOrCreate(['id' => 1], [
            'headline_line1' => 'Your Vision, Our Compliance.',
            'headline_line2' => 'Building Sustainable Business Foundations in Bangladesh',
            'paragraph' => 'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. We handle the complexity so you can lead with confidence.',
            'cta1_label' => 'Book a Consultation',
            'cta1_href' => '/contact',
            'cta2_label' => 'Explore Our Services',
            'cta2_href' => '/services',
        ]);
        foreach ([['hero/hero1.jpg', 'Hero slide 1'], ['hero/hero2..jpg', 'Hero slide 2'], ['hero/hero3.jpg', 'Hero slide 3']] as $i => [$path, $alt]) {
            HeroSlide::updateOrCreate(['image_path' => $path], ['alt_text' => $alt, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        $serviceCategories = [['Advisory', 'advisory'], ['Audit', 'audit'], ['Tax', 'tax'], ['Compliance', 'compliance'], ['HR', 'hr'], ['Strategy', 'strategy']];
        foreach ($serviceCategories as $i => [$label, $slug]) {
            $category = ServiceCategory::updateOrCreate(['slug' => $slug], ['label' => $label, 'sort_order' => $i + 1]);
            foreach ([
                ['Financial Services', '$75', 'Companies dislike the term turnaround consulting because it represents failure. The truth is that turnaround consulting represents success.', 'NEW'],
                ['Strategic planning', '$60', 'We allow our clients to invest in the right bonds, commodities, systems, and compliance foundations.', null],
            ] as $j => [$title, $price, $description, $badge]) {
                Service::updateOrCreate(['service_category_id' => $category->id, 'title' => $title], ['price' => $price, 'description' => $description, 'badge' => $badge, 'sort_order' => $j + 1, 'is_active' => true]);
            }
        }

        foreach (['Business Services', 'Travel & Aviation', 'Energy & Environment', 'Financial Services', 'Surface Transport & Logistics', 'Consumer Products'] as $i => $name) {
            $cat = CaseStudyCategory::updateOrCreate(['slug' => str($name)->slug()->toString()], ['name' => $name, 'sort_order' => $i + 1]);
            CaseStudy::updateOrCreate(['slug' => str($name.' case study')->slug()->toString()], [
                'case_study_category_id' => $cat->id,
                'title' => $i === 0 ? 'Healthcare giant overcomes merger in 2015' : $name.' compliance transformation',
                'summary' => 'A brief summary of the case study.',
                'body' => '<p>Full HTML content of the case study.</p>',
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }

        AboutSetting::updateOrCreate(['id' => 1], [
            'banner_label' => 'About Us',
            'hero_heading_line1' => 'Workshops',
            'hero_heading_line2' => 'that awesome!',
            'hero_paragraph' => 'We are a company that offers design and build services for you from initial sketches to the final construction.',
            'hero_image_path' => 'about/hero.png',
            'hero_cta_label' => 'get a quote',
            'hero_cta_href' => '/contact',
            'overview_paragraph1' => 'Greenland Business & Compliance is a leading advisory powerhouse in Bangladesh.',
            'overview_paragraph2' => 'We achieved our success because of how successfully we integrate with our clients.',
            'overview_callout' => 'Greenland continues to grow every day thanks to the confidence our clients have in us.',
            'mission_heading' => 'Our mission',
            'mission_intro' => 'Our renowned coaching programs will allow you to:',
            'approach_intro1' => 'Greenland Business & Compliance approaches every client business as if it were our own.',
            'approach_intro2' => 'The right approach is necessary for the right outcome.',
            'footer_cta_text' => 'LOOKING FOR A FIRST-CLASS BUSINESS PLAN CONSULTANT?',
            'footer_cta_button_label' => 'get a quote',
            'footer_cta_button_href' => '/contact',
        ]);

        foreach (['Work fewer hours — and make more money', 'Attract and retain quality, high-paying customers', 'Manage your time so you get more done in less time', 'Hone sharp leadership skills to manage your team', 'Cut expenses without sacrificing quality', 'Automate your business so you can leave for weeks'] as $i => $text) {
            MissionBullet::updateOrCreate(['text' => $text], ['sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([['1985', 'Start with a small service'], ['1990', 'First employees'], ['2001', 'First recognition'], ['2015', 'Greenland — corporation or family']] as $i => [$year, $title]) {
            TimelineMilestone::updateOrCreate(['year' => $year], ['title' => $title, 'description' => 'This milestone shaped the growth of Greenland Business & Compliance.', 'sort_order' => $i + 1]);
        }
        foreach ([['Travel and Aviation Consulting', 'Plane'], ['Business Services Consulting', 'TrendingUp'], ['Consumer Products Consulting', 'ShoppingCart'], ['Financial Services Consulting', 'Building2'], ['Energy and Environment Consulting', 'Zap'], ['TAX Services Consulting', 'Truck']] as $i => [$title, $icon]) {
            ApproachCard::updateOrCreate(['title' => $title], ['icon' => $icon, 'description' => 'We combine practical expertise with fact-based advisory support.', 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach (['Certificate of Achievement', 'Certificate of Recognition', 'Certificate of Excellence', 'Certificate of Incorporation'] as $i => $title) {
            Achievement::updateOrCreate(['title' => $title], ['sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach (['Aramiz Company', 'Adup Media LLC', 'Green Shield', 'Primo Software'] as $i => $name) {
            Partner::updateOrCreate(['name' => $name], ['industry' => 'Consulting Partner', 'location' => 'Bangladesh', 'description' => 'Strategic partnerships allow companies to expand and specialize without limitations.', 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([['Brandon Copperfield', 'Founder & CEO'], ['Clark Roberts', 'Chief Finance Officer'], ['Ashley Hardy', 'VP Sales and Marketing'], ['Dennis Norris', 'Chief Marketing Officer'], ['Gina Kennedy', 'Administrator'], ['Fernando Torres', 'Tax Consultant']] as $i => [$name, $role]) {
            TeamMember::updateOrCreate(['name' => $name], ['role' => $role, 'description' => 'A senior member of the Greenland advisory team.', 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach (['How many times do I have to tell you a few ways?', 'What is do I have to tell you a few lorem?', 'I have a technical problem I need resolved, who do I email?', 'What other services are you compatible with?', 'How many times do I have to tell you a few ways again?', 'What other services are you compatible with again?'] as $i => $question) {
            Faq::updateOrCreate(['question' => $question], ['answer' => 'Please contact our support team at support@greenlandcompliance.com for any technical inquiries or issues.', 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([['Damian Smulders', 'CEO, TechFlow', 'about', 'about/avatar1.png'], ['Cintia Le Cane', 'Chairman, Harmony Corporation', 'about', 'about/avatar2.png'], ['Amanda Seyford', 'Founder & CEO, Arcade Systems', 'case_studies', 'about/avatar3.png']] as $i => [$author, $role, $page, $avatar]) {
            Testimonial::updateOrCreate(['author' => $author], ['role' => $role, 'quote' => 'The results were clear, professional, and persuasive.', 'page' => $page, 'avatar_path' => $avatar, 'sort_order' => $i + 1, 'is_active' => true]);
        }

        foreach ([['Any Queries', 'contact@greenlandcompliance.com'], ['Help or Support', 'help@greenlandcompliance.com'], ['Job or Career', 'career@greenlandcompliance.com']] as $i => [$title, $email]) {
            ContactDepartment::updateOrCreate(['title' => $title], ['email' => $email, 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([['Government Gazette on Labor Law 2023', 'pdf', 'Gazette'], ['Company Compliance Timeline 2024', 'jpg', 'Timeline'], ['Industrial Safety Guidelines', 'pdf', 'Nirdeshika'], ['Business Ethics & Conduct Book', 'pdf', 'Books'], ['Environmental Regulations Handbook', 'word', 'Manual'], ['Taxation Policy Update', 'pdf', 'Govt. Gazette']] as $i => [$title, $format, $category]) {
            Publication::updateOrCreate(['title' => $title], ['format' => $format, 'category' => $category, 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([['Employee Onboarding Form', 'Human Resources', 'word', 'English'], ['Leave Application Template', 'Human Resources', 'excel', 'Bangla'], ['Performance Review Template', 'Human Resources', 'word', 'English'], ['Trade License Renewal Form', 'Legal & Compliance', 'word', 'Bangla'], ['Compliance Audit Checklist', 'Legal & Compliance', 'excel', 'English'], ['Annual Return Template', 'Legal & Compliance', 'excel', 'Bangla'], ['Expense Claim Form', 'Finance & Accounts', 'excel', 'English'], ['Tax Deduction Statement', 'Finance & Accounts', 'excel', 'Bangla']] as $i => [$title, $group, $format, $language]) {
            FormTemplate::updateOrCreate(['title' => $title], ['category_group' => $group, 'format' => $format, 'language' => $language, 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([['Greenland Compliance joins Global Safety Summit', 'Events', '2026-05-10', 'jpg'], ['New Labor Law Amendments: What you need to know', 'Regulatory', '2026-05-08', 'png'], ['Annual General Meeting Highlights 2025', 'Corporate', '2026-04-25', 'jpg'], ['Excellence in Compliance Award Won', 'Awards', '2026-04-20', 'png']] as $i => [$title, $category, $date, $format]) {
            NewsPost::updateOrCreate(['title' => $title], ['category' => $category, 'published_at' => $date, 'format' => $format, 'external_url' => null, 'sort_order' => $i + 1, 'is_active' => true]);
        }
        foreach ([['Privacy Policy', 'privacy'], ['Terms of Service', 'terms'], ['Cookie Settings', 'cookies']] as [$title, $slug]) {
            Page::updateOrCreate(['slug' => $slug], ['title' => $title, 'content' => '<h2>'.$title.'</h2><p>This page content is managed from the admin panel.</p>', 'is_active' => true]);
        }
    }
}
