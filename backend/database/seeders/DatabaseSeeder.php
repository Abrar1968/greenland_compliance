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
        $this->seedAdmin();
        $this->seedSiteSettings();
        $this->seedNavigation();
        $this->seedHero();
        $this->seedServices();
        $this->seedCaseStudies();
        $this->seedAbout();
        $this->seedResources();
        $this->seedPages();
    }

    private function seedAdmin(): void
    {
        Admin::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@greenlandcompliance.com')],
            [
                'name' => 'Greenland Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@1234')),
            ]
        );
    }

    private function seedSiteSettings(): void
    {
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
            'office_image_path' => null,
            'company_presentation_file' => null,
            'how_we_work_video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'map_embed_url' => 'https://www.google.com/maps?q=Bottola%20Bazar%2C%20Bhakurta%2C%20Savar%2C%20Dhaka-1313%2C%20Bangladesh&output=embed',
        ]);

        $links = [
            ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/greenland', 'sort_order' => 1],
            ['platform' => 'facebook', 'url' => 'https://facebook.com/greenland', 'sort_order' => 2],
            ['platform' => 'twitter', 'url' => 'https://twitter.com/greenland', 'sort_order' => 3],
            ['platform' => 'instagram', 'url' => 'https://instagram.com/greenland', 'sort_order' => 4],
        ];

        foreach ($links as $link) {
            SocialLink::updateOrCreate(['platform' => $link['platform']], $link + ['is_active' => true]);
        }
    }

    private function seedNavigation(): void
    {
        $items = [
            ['Home', '/', 'header', 1],
            ['Services', '/services', 'header', 2],
            ['Case Studies', '/case-studies', 'header', 3],
            ['About Us', '/about', 'header', 4],
            ['Contact US', '/contact', 'header', 5],
            ['Resources', '/resources', 'header', 6],
            ['Home', '/', 'footer_quick', 1],
            ['About Us', '/about', 'footer_quick', 2],
            ['Our Services', '/services', 'footer_quick', 3],
            ['Case Studies', '/case-studies', 'footer_quick', 4],
            ['Resources', '/resources', 'footer_quick', 5],
            ['Contact Us', '/contact', 'footer_quick', 6],
            ['Business Advisory', '/services', 'footer_services', 1],
            ['Audit & Assurance', '/services', 'footer_services', 2],
            ['Taxation Services', '/services', 'footer_services', 3],
            ['Regulatory Compliance', '/services', 'footer_services', 4],
            ['Human Capital Management', '/services', 'footer_services', 5],
            ['Strategy Consulting', '/services', 'footer_services', 6],
            ['Privacy Policy', '/privacy', 'footer_policy', 1],
            ['Terms of Service', '/terms', 'footer_policy', 2],
            ['Cookie Settings', '/cookies', 'footer_policy', 3],
        ];

        foreach ($items as [$label, $href, $location, $sortOrder]) {
            NavItem::updateOrCreate(
                ['label' => $label, 'location' => $location],
                ['href' => $href, 'sort_order' => $sortOrder, 'is_active' => true]
            );
        }
    }

    private function seedHero(): void
    {
        HeroSetting::updateOrCreate(['id' => 1], [
            'headline_line1' => 'Your Vision, Our Compliance.',
            'headline_line2' => 'Building Sustainable Business Foundations in Bangladesh',
            'paragraph' => 'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. We handle the complexity so you can lead with confidence.',
            'cta1_label' => 'Book a Consultation',
            'cta1_href' => '/contact',
            'cta2_label' => 'Explore Our Services',
            'cta2_href' => '/services',
        ]);

        foreach ([['hero/hero1.jpg', 'Hero slide 1'], ['hero/hero2..jpg', 'Hero slide 2'], ['hero/hero3.jpg', 'Hero slide 3']] as $index => [$path, $alt]) {
            HeroSlide::updateOrCreate(['image_path' => $path], [
                'alt_text' => $alt,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedServices(): void
    {
        $categories = [
            ['Advisory', 'advisory'],
            ['Audit', 'audit'],
            ['Consulting', 'consulting'],
            ['Human Capital', 'human-capital'],
            ['Mergers & Acquisitions', 'mergers-acquisitions'],
            ['Operations', 'operations'],
            ['Regulatory', 'regulatory'],
            ['Strategy', 'strategy'],
            ['Tax', 'tax'],
        ];

        foreach ($categories as $index => [$label, $slug]) {
            ServiceCategory::updateOrCreate(['slug' => $slug], ['label' => $label, 'sort_order' => $index + 1]);
        }

        $serviceRows = [
            ['advisory', 'Financial Services', '$75', 'Companies dislike the term \'turnaround consulting\' because it represents failure. The truth is that turnaround consulting represents success.', 'NEW', 1],
            ['advisory', 'Strategic planning', '$60', 'Bonds and commodities are much more stable than stocks and trades. We allow our clients to invest in the right bonds & commodities.', null, 2],
            ['advisory', 'Audit & Assurance', '$115', 'Audit and assurance is all about meticulous data analysis. Everything needs to be checked, double checked, and triple checked.', 'SPECIAL', 3],
            ['advisory', 'Trades & Stocks', '$57', 'This allows us to specialize in all dimensions of trades and stocks, because we have a specialist within the team for every scenario.', null, 4],
            ['advisory', 'Strategic Planning', '$35', 'We work with our clients and do a deep analysis of their business. We help prepare possible outcomes to different decisions.', 'NEW', 5],
            ['advisory', 'Financial Projections', '$80', 'This stops companies from taking drastic measures like downsizing or closing down sites; those things happen only with no.', null, 6],
            ['advisory', 'International Business Opportunities', '$58', 'We allow you to enter international waters without having to worry about making a mistake, as experience.', null, 7],
            ['advisory', 'Business Planning, Strategy & Execution', '$49', 'Execution is the single most important part of the whole process, poor execution can result in a lot of lost time and money.', 'NEW', 8],
            ['audit', 'External Audit', '$200', 'Comprehensive external auditing services for large corporations and SMEs to ensure regulatory compliance.', 'NEW', 1],
            ['audit', 'Internal Control Review', '$150', 'Evaluation and improvement of your internal control systems to mitigate risks and enhance operational efficiency.', null, 2],
        ];

        foreach (array_slice($categories, 2) as [$label, $slug]) {
            $serviceRows[] = [$slug, $label.' Consulting', '$100', 'Initial '.$label.' service placeholder managed from the admin panel.', null, 1];
        }

        foreach ($serviceRows as [$categorySlug, $title, $price, $description, $badge, $sortOrder]) {
            $category = ServiceCategory::where('slug', $categorySlug)->firstOrFail();
            Service::updateOrCreate(
                ['service_category_id' => $category->id, 'title' => $title],
                ['price' => $price, 'description' => $description, 'badge' => $badge, 'sort_order' => $sortOrder, 'is_active' => true]
            );
        }
    }

    private function seedCaseStudies(): void
    {
        $categories = [
            'Business Services' => 'business-services',
            'Travel & Aviation' => 'travel-aviation',
            'Energy & Environment' => 'energy-environment',
            'Financial Services' => 'financial-services',
            'Surface Transport & Logistics' => 'surface-transport-logistics',
            'Consumer Products' => 'consumer-products',
        ];

        $index = 1;
        foreach ($categories as $name => $slug) {
            CaseStudyCategory::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'sort_order' => $index,
            ]);
            $index++;
        }

        $caseStudies = [
            ['Business Services', 'Healthcare giant overcomes merger in 2015'],
            ['Travel & Aviation', 'Focus on core delivers growth for retailer trading'],
            ['Business Services', 'Transformation sparks financial income for all'],
            ['Business Services', 'Increased sales productivity frees selling time and saves millions'],
            ['Energy & Environment', 'Constructing a best-in-class global procurement'],
            ['Business Services', 'Turning around a reactive pharma supply chain'],
            ['Financial Services', 'Leading consumer products companies'],
            ['Surface Transport & Logistics', 'Bain helps transportation & logistics companies'],
            ['Energy & Environment', 'Developing a strategy and roadmap for clients'],
            ['Business Services', 'Constructing the best-in-class global'],
            ['Surface Transport & Logistics', 'Demand as transportation services as'],
            ['Consumer Products', 'Pricing games: A technology company'],
        ];

        foreach ($caseStudies as $index => [$categoryName, $title]) {
            $category = CaseStudyCategory::where('slug', $categories[$categoryName])->firstOrFail();
            CaseStudy::updateOrCreate(['slug' => str($title)->slug()->toString()], [
                'case_study_category_id' => $category->id,
                'title' => $title,
                'image_path' => null,
                'summary' => null,
                'body' => '<p>Full HTML content for '.$title.'.</p>',
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedAbout(): void
    {
        AboutSetting::updateOrCreate(['id' => 1], [
            'banner_label' => 'About Us',
            'hero_heading_line1' => 'Workshops',
            'hero_heading_line2' => 'that awesome!',
            'hero_paragraph' => 'We are a company that offers design and build services for you from initial sketches to the final construction.',
            'hero_image_path' => 'about/hero.png',
            'hero_cta_label' => 'get a quote',
            'hero_cta_href' => '/contact',
            'overview_paragraph1' => 'Greenland Business & Compliance is a leading advisory powerhouse in Bangladesh. We began our operations a few decades ago and have grown due to excellent relationships with our clients.',
            'overview_paragraph2' => 'We achieved our success because of how successfully we integrate with our clients. One complaint many people have about consultants is that they can be disruptive — our clients face no such issues.',
            'overview_callout' => 'Greenland continues to grow every day thanks to the confidence our clients have in us. We cover many industries such as financial, energy, business services, and consumer products.',
            'mission_heading' => 'Our mission',
            'mission_intro' => 'Our renowned coaching programs will allow you to:',
            'approach_intro1' => 'Greenland Business & Compliance approaches every client\'s business as if it were our own.',
            'approach_intro2' => 'The right approach is necessary for the right outcome. We know that in order to maximize the potential of success for your company we need to shape our expert advice in a way that applies to your way of doing business.',
            'footer_cta_text' => 'LOOKING FOR A FIRST-CLASS BUSINESS PLAN CONSULTANT?',
            'footer_cta_button_label' => 'get a quote',
            'footer_cta_button_href' => '/contact',
        ]);

        foreach ([
            'Work fewer hours — and make more money',
            'Attract and retain quality, high-paying customers',
            'Manage your time so you\'ll get more done in less time',
            'Hone sharp leadership skills to manage your team',
            'Cut expenses without sacrificing quality',
            'Automate your business, so you can leave for days, weeks, or even months',
        ] as $index => $text) {
            MissionBullet::updateOrCreate(['text' => $text], ['sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([
            ['1985', 'Start with a small service', 'This was the year when we started our company. We had no idea how far we would go, we weren\'t even sure that we would be able to survive for a few years. What drove us to start the company was the understanding that we could provide a service no one else was providing.'],
            ['1990', 'First employees', 'This was the first period when Greenland Business & Compliance actually felt like it would stick around for a while. We realized we were growing more stable and expanding in the same area. We needed a new office as we had severely outgrown the last one.'],
            ['2001', 'First recognition', 'By this time we were a well-known name within the industry. We had been prominent members of the industry for more than 16 years, and worked for some of the biggest clients in the industry.'],
            ['2015', 'Greenland — corporation or family', 'Our journey has only brought us higher. Information Technology completely changes the way we analyze and present data. We have embraced new technologies and have ensured that our clients receive cutting edge analytics.'],
        ] as $index => [$year, $title, $description]) {
            TimelineMilestone::updateOrCreate(['year' => $year], ['title' => $title, 'description' => $description, 'sort_order' => $index + 1]);
        }

        foreach ([
            ['Travel and Aviation Consulting', 'Plane', 'Armed with statistical knowledge, technical expertise, and fact based prediction, we allow your business to truly soar.'],
            ['Business Services Consulting', 'TrendingUp', 'We help you shape and position your business services in a way that enhances the output of your clients.'],
            ['Consumer Products Consulting', 'ShoppingCart', 'We help companies dealing in consumer products create and present products that perfectly blend in with the zeitgeist.'],
            ['Financial Services Consulting', 'Building2', 'Our financial experts help you analyze financial data, to create a rock steady financial foundation.'],
            ['Energy and Environment Consulting', 'Zap', 'We work with energy companies to increase their efficiency and eliminate any environmentally harmful practices.'],
            ['TAX Services Consulting', 'Truck', 'We are a company that offers design and build services for you from initial sketches to the final construction.'],
        ] as $index => [$title, $icon, $description]) {
            ApproachCard::updateOrCreate(['title' => $title], ['icon' => $icon, 'description' => $description, 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach (['Certificate of Achievement', 'Certificate of Recognition', 'Certificate of Excellence', 'Certificate of Incorporation'] as $index => $title) {
            Achievement::updateOrCreate(['title' => $title], ['image_path' => null, 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([
            ['Aramiz Company', 'Athletic Performance Tracking Devices', 'Escondido, CA', 'We aren\'t such an agile and dependable organization just because of our own team; we also have a fantastic network of partners who compliment our services.'],
            ['Adup Media LLC', 'Media & Marketing Consulting', 'Walnut Creek, CA', 'Our partners are the top companies in their own respective industries, and are known to deliver high quality services and products.'],
            ['Green Shield', 'Heart Transplant Monitoring Technology', 'Charlotte, NC', 'Strategic partnerships allow companies to expand and specialize without limitations. Instead of spending money perfecting a new thing, we prefer to perfect our own services.'],
            ['Primo Software', 'Software Development', 'Manitowoc, WI', 'Our customers trust us so much that they often come to us with problems beyond the scope of financial consultancy.'],
        ] as $index => [$name, $industry, $location, $description]) {
            Partner::updateOrCreate(['name' => $name], ['industry' => $industry, 'location' => $location, 'description' => $description, 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([
            ['Brandon Copperfield', 'Founder & CEO', 'The founder of Greenland Business & Compliance, he has been the captain of this ship from the beginning and has sailed it to great heights.'],
            ['Clark Roberts', 'Chief Finance Officer', 'Being the CFO in the Financial Industry is a tough task, thankfully he was here to man the helm.'],
            ['Ashley Hardy', 'VP Sales and Marketing', 'She is an accomplished business developer. Her skills at creating relationships with clients are unparalleled.'],
            ['Dennis Norris', 'Chief Marketing Officer', 'He has helped Greenland reach new heights and enter new markets. His skills of understanding audiences are exceptional.'],
            ['Gina Kennedy', 'Administrator', 'As we help other companies grow, she helps us grow. She handles all the internal work at Greenland with efficiency.'],
            ['Fernando Torres', 'Tax Consultant', 'Tax laws and regulations are some of the most complicated and infuriating parts of the financial world — he navigates them masterfully.'],
        ] as $index => [$name, $role, $description]) {
            TeamMember::updateOrCreate(['name' => $name], ['role' => $role, 'description' => $description, 'profile_slug' => str($name)->slug()->toString(), 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([
            ['How many times do I have to tell you a few ways?', 'Progressively generate synergistic total linkage through cross-media intellectual capital. Enthusiastically parallel task team building e-tailers without standards compliant initiatives.'],
            ['What is do I have to tell you a few lorem?', 'Greenland Business & Compliance continues to grow every day thanks to the confidence our clients have in us. We cover many industries such as financial, energy, business services, and consumer products.'],
            ['I have a technical problem I need resolved, who do I email?', 'Please contact our technical support team at support@greenlandcompliance.com for any technical inquiries or issues.'],
            ['What other services are you compatible with?', 'Our systems are designed to be highly compatible with modern enterprise software including SAP, Oracle, and Microsoft Dynamics.'],
            ['How many times do I have to tell you a few ways again?', 'This is another example of a frequently asked question to demonstrate the accordion functionality.'],
            ['What other services are you compatible with again?', 'We offer full integration services for a wide range of industry-standard tools.'],
        ] as $index => [$question, $answer]) {
            Faq::updateOrCreate(['question' => $question], ['answer' => $answer, 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([
            ['Damian Smulders', 'CEO, TechFlow', 'The results were clear, professional, and persuasive, and the investors and advisors who have seen the materials loved them. They know what investors want.', 'about/avatar1.png'],
            ['Cintia Le Cane', 'Chairman, Harmony Corporation', 'We thought a lot before choosing our compliance partner because we wanted to be sure our investment would yield results. Greenland Compliance is an invaluable partner.', 'about/avatar2.png'],
            ['Amanda Seyford', 'Founder & CEO, Arcade Systems', 'We were amazed by how little effort was required on our part to have Greenland prepare these materials. We exchanged a few phone calls. An invaluable partner.', 'about/avatar3.png'],
        ] as $index => [$author, $role, $quote, $avatar]) {
            Testimonial::updateOrCreate(['author' => $author], ['role' => $role, 'quote' => $quote, 'avatar_path' => $avatar, 'page' => 'global', 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([['Any Queries', 'contact@greenlandcompliance.com'], ['Help or Support', 'help@greenlandcompliance.com'], ['Job or Career', 'career@greenlandcompliance.com']] as $index => [$title, $email]) {
            ContactDepartment::updateOrCreate(['title' => $title], ['email' => $email, 'sort_order' => $index + 1, 'is_active' => true]);
        }
    }

    private function seedResources(): void
    {
        foreach ([
            ['Government Gazette on Labor Law 2023', 'pdf', 'Gazette'],
            ['Company Compliance Timeline 2024', 'jpg', 'Timeline'],
            ['Industrial Safety Guidelines', 'pdf', 'Nirdeshika'],
            ['Business Ethics & Conduct Book', 'pdf', 'Books'],
            ['Environmental Regulations Handbook', 'word', 'Manual'],
            ['Taxation Policy Update', 'pdf', 'Govt. Gazette'],
        ] as $index => [$title, $format, $category]) {
            Publication::updateOrCreate(['title' => $title], ['format' => $format, 'category' => $category, 'file_path' => null, 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([
            ['Employee Onboarding Form', 'Human Resources', 'word', 'English'],
            ['Leave Application Template', 'Human Resources', 'excel', 'Bangla'],
            ['Performance Review Template', 'Human Resources', 'word', 'English'],
            ['Trade License Renewal Form', 'Legal & Compliance', 'word', 'Bangla'],
            ['Compliance Audit Checklist', 'Legal & Compliance', 'excel', 'English'],
            ['Annual Return Template', 'Legal & Compliance', 'excel', 'Bangla'],
            ['Expense Claim Form', 'Finance & Accounts', 'excel', 'English'],
            ['Tax Deduction Statement', 'Finance & Accounts', 'excel', 'Bangla'],
        ] as $index => [$title, $group, $format, $language]) {
            FormTemplate::updateOrCreate(['title' => $title], ['category_group' => $group, 'format' => $format, 'language' => $language, 'file_path' => null, 'sort_order' => $index + 1, 'is_active' => true]);
        }

        foreach ([
            ['Greenland Compliance joins Global Safety Summit', 'Events', '2026-05-10', 'jpg'],
            ['New Labor Law Amendments: What you need to know', 'Regulatory', '2026-05-08', 'png'],
            ['Annual General Meeting Highlights 2025', 'Corporate', '2026-04-25', 'jpg'],
            ['Excellence in Compliance Award Won', 'Awards', '2026-04-20', 'png'],
        ] as $index => [$title, $category, $date, $format]) {
            NewsPost::updateOrCreate(['title' => $title], ['category' => $category, 'published_at' => $date, 'format' => $format, 'body' => null, 'external_url' => null, 'sort_order' => $index + 1, 'is_active' => true]);
        }
    }

    private function seedPages(): void
    {
        foreach ([
            ['privacy', 'Privacy Policy', '<p>Privacy policy content goes here.</p>'],
            ['terms', 'Terms of Service', '<p>Terms of service content goes here.</p>'],
            ['cookies', 'Cookie Settings', '<p>Cookie settings information goes here.</p>'],
        ] as [$slug, $title, $content]) {
            Page::updateOrCreate(['slug' => $slug], ['title' => $title, 'content' => $content, 'is_active' => true]);
        }
    }
}
