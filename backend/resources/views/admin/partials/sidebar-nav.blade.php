@php
    $groups = [
        'Main' => [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'D', 'active' => ['admin.dashboard']],
        ],
        'Site' => [
            ['route' => 'admin.site-settings.edit', 'label' => 'Site Settings', 'icon' => 'S', 'active' => ['admin.site-settings.*']],
            ['route' => 'admin.social-links.index', 'label' => 'Social Links', 'icon' => 'L', 'active' => ['admin.social-links.*']],
        ],
        'Homepage' => [
            ['route' => 'admin.hero-settings.edit', 'label' => 'Hero Settings', 'icon' => 'H', 'active' => ['admin.hero-settings.*']],
            ['route' => 'admin.hero-slides.index', 'label' => 'Hero Slides', 'icon' => 'B', 'active' => ['admin.hero-slides.*']],
        ],
        'Navigation' => [
            ['route' => 'admin.nav-items.index', 'label' => 'Nav Items', 'icon' => 'N', 'active' => ['admin.nav-items.*']],
        ],
        'Services' => [
            ['route' => 'admin.service-categories.index', 'label' => 'Service Categories', 'icon' => 'C', 'active' => ['admin.service-categories.*']],
            ['route' => 'admin.services.index', 'label' => 'Services', 'icon' => 'R', 'active' => ['admin.services.*']],
        ],
        'Case Studies' => [
            ['route' => 'admin.case-study-categories.index', 'label' => 'Case Study Categories', 'icon' => 'K', 'active' => ['admin.case-study-categories.*']],
            ['route' => 'admin.case-studies.index', 'label' => 'Case Studies', 'icon' => 'Y', 'active' => ['admin.case-studies.*']],
        ],
        'About' => [
            ['route' => 'admin.about-settings.edit', 'label' => 'About Settings', 'icon' => 'A', 'active' => ['admin.about-settings.*']],
            ['route' => 'admin.timeline-milestones.index', 'label' => 'Timeline Milestones', 'icon' => 'T', 'active' => ['admin.timeline-milestones.*']],
            ['route' => 'admin.mission-bullets.index', 'label' => 'Mission Bullets', 'icon' => 'M', 'active' => ['admin.mission-bullets.*']],
            ['route' => 'admin.approach-cards.index', 'label' => 'Approach Cards', 'icon' => 'P', 'active' => ['admin.approach-cards.*']],
            ['route' => 'admin.achievements.index', 'label' => 'Achievements', 'icon' => 'G', 'active' => ['admin.achievements.*']],
            ['route' => 'admin.partners.index', 'label' => 'Partners', 'icon' => 'O', 'active' => ['admin.partners.*']],
            ['route' => 'admin.team-members.index', 'label' => 'Team Members', 'icon' => 'U', 'active' => ['admin.team-members.*']],
        ],
        'Engagement' => [
            ['route' => 'admin.testimonials.index', 'label' => 'Testimonials', 'icon' => 'Q', 'active' => ['admin.testimonials.*']],
            ['route' => 'admin.faqs.index', 'label' => 'FAQs', 'icon' => 'F', 'active' => ['admin.faqs.*']],
        ],
        'Contact' => [
            ['route' => 'admin.contact-departments.index', 'label' => 'Contact Departments', 'icon' => 'E', 'active' => ['admin.contact-departments.*']],
            ['route' => 'admin.contact-messages.index', 'label' => 'Contact Messages', 'icon' => 'I', 'active' => ['admin.contact-messages.*']],
        ],
        'Resources' => [
            ['route' => 'admin.publications.index', 'label' => 'Publications', 'icon' => 'J', 'active' => ['admin.publications.*']],
            ['route' => 'admin.form-templates.index', 'label' => 'Forms & Templates', 'icon' => 'F', 'active' => ['admin.form-templates.*']],
            ['route' => 'admin.news-posts.index', 'label' => 'News Posts', 'icon' => 'W', 'active' => ['admin.news-posts.*']],
        ],
        'CMS Pages' => [
            ['route' => 'admin.pages.index', 'label' => 'Pages', 'icon' => 'P', 'active' => ['admin.pages.*']],
        ],
    ];
@endphp

<nav class="admin-nav">
    @foreach($groups as $group => $links)
        <div class="admin-nav-section">{{ $group }}</div>
        @foreach($links as $link)
            <a class="admin-nav-link {{ request()->routeIs(...$link['active']) ? 'admin-nav-link-active' : '' }}" href="{{ route($link['route']) }}">
                <span class="admin-nav-icon">{{ $link['icon'] }}</span>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    @endforeach
</nav>
