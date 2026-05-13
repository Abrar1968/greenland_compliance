# Greenland Business & Compliance — Laravel Backend Specification

Project: Greenland Business & Compliance
Repository: https://github.com/Abrar1968/greenland_compliance.git
Backend path inside repo: `backend/`
Document purpose: Complete specification for a Laravel backend that powers the admin panel and serves a REST API consumed by the Next.js frontend. Every piece of static data currently hard-coded in the frontend must be seeded here and manageable through the admin panel.

---

## 1. Technology Stack

The backend uses the following technology choices. Understanding why each was chosen helps you make decisions when the spec does not cover edge cases.

**Laravel 12** is the application framework. It provides routing, Eloquent ORM, Artisan CLI, Blade templating, middleware, queues, and the file storage abstraction. Laravel 12 uses the modern slim application skeleton; the `bootstrap/app.php` file is the routing, middleware, and exception configuration entry point instead of the old `Kernel` classes.

**PHP 8.2 or higher** is required. Modern PHP features used heavily include constructor property promotion, enums, match expressions, and readonly properties.

**MySQL 8** is the database, matching the existing Prisma schema datasource already chosen by the frontend team.

**Laravel Sanctum** handles session-based admin authentication. Because the admin panel is a Blade/server-rendered app, session cookies are appropriate. Sanctum is also used to issue API tokens if token-based API access is ever needed by the frontend.

**Blade templating** with **Tailwind CSS v4** and **Alpine.js 3** powers the admin panel UI. Tailwind CSS v4 is compiled through Laravel's Vite pipeline using the official Tailwind v4 Vite plugin included by the Laravel 12 skeleton. Alpine.js is loaded from CDN for lightweight admin interactions.

**Laravel Storage (public disk)** handles all uploaded files. The `storage/app/public` directory is symlinked to `public/storage` via `php artisan storage:link`. All uploaded images and files are served from `/storage/...`.

**Laravel Telescope** is installed in development for debugging. It is disabled in production.

---

## 2. Repository Layout

Inside the repository the backend lives at `backend/`. The full directory tree after scaffolding looks like this:

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── Auth/
│   │   │   │   │   └── LoginController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── SiteSettingsController.php
│   │   │   │   ├── HeroSettingsController.php
│   │   │   │   ├── HeroSlideController.php
│   │   │   │   ├── NavItemController.php
│   │   │   │   ├── ServiceCategoryController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── CaseStudyCategoryController.php
│   │   │   │   ├── CaseStudyController.php
│   │   │   │   ├── TestimonialController.php
│   │   │   │   ├── TimelineMilestoneController.php
│   │   │   │   ├── AboutSettingsController.php
│   │   │   │   ├── MissionBulletController.php
│   │   │   │   ├── ApproachCardController.php
│   │   │   │   ├── AchievementController.php
│   │   │   │   ├── PartnerController.php
│   │   │   │   ├── TeamMemberController.php
│   │   │   │   ├── FaqController.php
│   │   │   │   ├── ContactDepartmentController.php
│   │   │   │   ├── PublicationController.php
│   │   │   │   ├── FormTemplateController.php
│   │   │   │   ├── NewsPostController.php
│   │   │   │   ├── ContactMessageController.php
│   │   │   │   ├── SocialLinkController.php
│   │   │   │   ├── ReorderController.php
│   │   │   │   └── PageController.php
│   │   │   └── Api/
│   │   │       └── V1/
│   │   │           ├── SiteController.php
│   │   │           ├── HeroController.php
│   │   │           ├── NavigationController.php
│   │   │           ├── ServiceController.php
│   │   │           ├── CaseStudyController.php
│   │   │           ├── AboutController.php
│   │   │           ├── ContactController.php
│   │   │           ├── ResourceController.php
│   │   │           ├── TestimonialController.php
│   │   │           └── PageController.php
│   │   └── Middleware/
│   │       └── EnsureAdminAuthenticated.php
│   ├── Models/
│   │   ├── Admin.php
│   │   ├── SiteSetting.php
│   │   ├── SocialLink.php
│   │   ├── NavItem.php
│   │   ├── HeroSlide.php
│   │   ├── HeroSetting.php
│   │   ├── ServiceCategory.php
│   │   ├── Service.php
│   │   ├── CaseStudyCategory.php
│   │   ├── CaseStudy.php
│   │   ├── Testimonial.php
│   │   ├── TimelineMilestone.php
│   │   ├── AboutSetting.php
│   │   ├── MissionBullet.php
│   │   ├── ApproachCard.php
│   │   ├── Achievement.php
│   │   ├── Partner.php
│   │   ├── TeamMember.php
│   │   ├── Faq.php
│   │   ├── ContactDepartment.php
│   │   ├── Publication.php
│   │   ├── FormTemplate.php
│   │   ├── NewsPost.php
│   │   ├── ContactMessage.php
│   │   └── Page.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/
│   │   └── (all migration files listed in section 4)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── (individual seeders listed in section 5)
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── admin.blade.php
│       ├── admin/
│       │   ├── auth/
│       │   │   └── login.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── site-settings/
│       │   │   └── edit.blade.php
│       │   ├── hero-slides/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── nav-items/
│       │   │   ├── index.blade.php
│       │   │   └── (create/edit)
│       │   ├── services/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── service-categories/
│       │   ├── case-studies/
│       │   ├── case-study-categories/
│       │   ├── about/
│       │   ├── team-members/
│       │   ├── testimonials/
│       │   ├── partners/
│       │   ├── faqs/
│       │   ├── achievements/
│       │   ├── timeline-milestones/
│       │   ├── approach-cards/
│       │   ├── mission-bullets/
│       │   ├── publications/
│       │   ├── form-templates/
│       │   ├── news-posts/
│       │   ├── contact-departments/
│       │   ├── contact-messages/
│       │   ├── social-links/
│       │   └── pages/
│       └── components/
│           ├── admin-table.blade.php
│           ├── form-input.blade.php
│           ├── form-select.blade.php
│           ├── form-textarea.blade.php
│           ├── image-preview.blade.php
│           └── badge-select.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── config/
│   └── cors.php
├── storage/
│   └── app/public/
│       ├── hero/
│       ├── logo/
│       ├── about/
│       ├── team/
│       ├── achievements/
│       ├── case-studies/
│       ├── resources/
│       └── news/
└── .env.example
```

---

## 3. Environment Configuration

The `.env.example` file below shows all required environment variables. Copy it to `.env` and fill in real values.

```dotenv
APP_NAME="Greenland Business & Compliance Admin"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000
ADMIN_PANEL_URL=http://localhost:8000/admin

# The Next.js frontend origin(s) used for CORS
FRONTEND_URL=http://localhost:3000
FRONTEND_URLS=http://localhost:3000,https://greenlandcompliance.com,https://www.greenlandcompliance.com

# Production deployment examples:
# APP_URL=<BACKEND_URL>
# ADMIN_PANEL_URL=<ADMIN_PANEL_URL>
# FRONTEND_URL=https://greenlandcompliance.com
# FRONTEND_URLS=https://greenlandcompliance.com,https://www.greenlandcompliance.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=greenland_compliance
DB_USERNAME=root
DB_PASSWORD=

# Filesystem — all uploads go here; must run: php artisan storage:link
FILESYSTEM_DISK=public

# Session driver for admin panel auth
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Admin panel seeded credentials (used by AdminSeeder only)
ADMIN_EMAIL=admin@greenlandcompliance.com
ADMIN_PASSWORD=Admin@1234
```

`APP_URL` is the backend base URL and is used to generate absolute storage URLs returned by the API. `ADMIN_PANEL_URL` is the externally reachable admin panel URL; it may be `<BACKEND_URL>/admin` or a separate admin subdomain, depending on deployment. `FRONTEND_URL` is the canonical public frontend URL and must be `https://greenlandcompliance.com` in production. `FRONTEND_URLS` is the full comma-separated CORS allow-list for browser requests.

---

## 4. Database Migrations

Each migration below is a separate file. Run them all with `php artisan migrate`. Migrations are listed in dependency order (no foreign key before the referenced table).

### 4.1 admins table

```php
// database/migrations/2024_01_01_000001_create_admins_table.php
Schema::create('admins', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

### 4.2 site_settings table

A single-row settings table. The application always works with `SiteSetting::first()` or `SiteSetting::firstOrCreate([])`.

```php
Schema::create('site_settings', function (Blueprint $table) {
    $table->id();
    $table->string('site_name')->default('Greenland Business & Compliance');
    $table->string('meta_title')->default('Greenland Business & Compliance');
    $table->text('meta_description')->nullable();
    $table->string('logo_path')->nullable();            // stored in storage/app/public/logo/
    $table->string('primary_phone')->nullable();
    $table->string('primary_email')->nullable();
    $table->string('address')->nullable();
    $table->string('business_hours')->nullable();
    $table->text('footer_description')->nullable();
    $table->string('footer_cta_title')->nullable();
    $table->text('footer_cta_text')->nullable();
    $table->string('footer_cta_button_label')->nullable();
    $table->string('footer_cta_button_href')->nullable();
    $table->string('map_embed_url', 1000)->nullable();
    $table->string('copyright_text')->nullable();
    $table->string('office_image_path')->nullable();    // contact page office photo
    $table->string('company_presentation_file')->nullable(); // downloadable .pdf
    $table->string('how_we_work_video_url')->nullable(); // YouTube embed URL
    $table->timestamps();
});
```

### 4.3 social_links table

```php
Schema::create('social_links', function (Blueprint $table) {
    $table->id();
    // platform values: linkedin, facebook, twitter, instagram, youtube, whatsapp
    $table->string('platform');
    $table->string('url');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.4 nav_items table

```php
Schema::create('nav_items', function (Blueprint $table) {
    $table->id();
    $table->string('label');
    $table->string('href');
    // location: header | footer_quick | footer_services | footer_policy
    $table->string('location')->default('header');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.5 hero_settings table

```php
// Single-row table — the overall hero copy/CTAs
Schema::create('hero_settings', function (Blueprint $table) {
    $table->id();
    $table->string('headline_line1')->default('Your Vision, Our Compliance.');
    $table->string('headline_line2')->default('Building Sustainable Business Foundations in Bangladesh');
    $table->text('paragraph')->nullable();
    $table->string('cta1_label')->default('Book a Consultation');
    $table->string('cta1_href')->default('/contact');
    $table->string('cta2_label')->default('Explore Our Services');
    $table->string('cta2_href')->default('/services');
    $table->timestamps();
});
```

### 4.6 hero_slides table

```php
Schema::create('hero_slides', function (Blueprint $table) {
    $table->id();
    $table->string('image_path');       // relative to storage/ e.g. hero/hero1.jpg
    $table->string('alt_text')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.7 service_categories table

```php
Schema::create('service_categories', function (Blueprint $table) {
    $table->id();
    $table->string('label');            // Display label e.g. "Advisory"
    $table->string('slug')->unique();   // e.g. "advisory"
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

### 4.8 services table

```php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('service_category_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('price');            // stored as string e.g. "BDT 7,500" or "$75"
    $table->text('description');
    // badge: null | NEW | SPECIAL
    $table->string('badge')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.9 case_study_categories table

```php
Schema::create('case_study_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

### 4.10 case_studies table

```php
Schema::create('case_studies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('case_study_category_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('image_path')->nullable();   // stored in storage/app/public/case-studies/
    $table->text('summary')->nullable();
    $table->longText('body')->nullable();       // for future detail page
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.11 testimonials table

Testimonials are shared across pages. The `page` column controls where each testimonial appears.

```php
Schema::create('testimonials', function (Blueprint $table) {
    $table->id();
    $table->string('author');
    $table->string('role');
    $table->text('quote');
    $table->string('avatar_path')->nullable();
    // page: about | case_studies | global (shown everywhere)
    $table->string('page')->default('global');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.12 timeline_milestones table

```php
Schema::create('timeline_milestones', function (Blueprint $table) {
    $table->id();
    $table->string('year', 10);
    $table->string('title');
    $table->text('description');
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

### 4.13 about_settings table

```php
// Single-row table for About page content blocks
Schema::create('about_settings', function (Blueprint $table) {
    $table->id();
    $table->string('banner_label')->default('About Us');
    // Hero section
    $table->string('hero_heading_line1')->default('Workshops');
    $table->string('hero_heading_line2')->default('that awesome!');
    $table->text('hero_paragraph')->nullable();
    $table->string('hero_image_path')->nullable();
    $table->string('hero_cta_label')->default('get a quote');
    $table->string('hero_cta_href')->nullable();        // links to /contact by default
    // Overview
    $table->text('overview_paragraph1')->nullable();
    $table->text('overview_paragraph2')->nullable();
    $table->text('overview_callout')->nullable();
    $table->string('mission_heading')->default('Our mission');
    $table->string('mission_intro')->nullable();
    // Approach
    $table->text('approach_intro1')->nullable();
    $table->text('approach_intro2')->nullable();
    // Footer CTA
    $table->string('footer_cta_text')->nullable();
    $table->string('footer_cta_button_label')->default('get a quote');
    $table->string('footer_cta_button_href')->nullable();
    $table->timestamps();
});
```

### 4.14 mission_bullets table

```php
Schema::create('mission_bullets', function (Blueprint $table) {
    $table->id();
    $table->string('text');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.15 approach_cards table

```php
Schema::create('approach_cards', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('icon');             // Lucide icon name e.g. "Plane", "TrendingUp"
    $table->text('description');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.16 achievements table

```php
Schema::create('achievements', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('image_path')->nullable();   // stored in storage/app/public/achievements/
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.17 partners table

```php
Schema::create('partners', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('industry')->nullable();
    $table->string('location')->nullable();
    $table->text('description')->nullable();
    $table->string('logo_path')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.18 team_members table

```php
Schema::create('team_members', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('role');
    $table->text('description')->nullable();
    $table->string('image_path')->nullable();   // stored in storage/app/public/team/
    $table->string('profile_slug')->nullable(); // for future profile page
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.19 faqs table

```php
Schema::create('faqs', function (Blueprint $table) {
    $table->id();
    $table->text('question');
    $table->text('answer');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.20 contact_departments table

```php
Schema::create('contact_departments', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('email');
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.21 publications table

```php
Schema::create('publications', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    // format: pdf | word | excel | jpg | png
    $table->string('format');
    $table->string('category');         // e.g. "Gazette", "Timeline", "Books"
    $table->string('file_path')->nullable();    // stored in storage/app/public/resources/
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.22 form_templates table

```php
Schema::create('form_templates', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('category_group');   // e.g. "Human Resources", "Legal & Compliance"
    $table->string('format');           // word | excel
    // language: English | Bangla
    $table->string('language')->default('English');
    $table->string('file_path')->nullable();
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.23 news_posts table

```php
Schema::create('news_posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('category');
    $table->date('published_at');
    $table->string('image_path')->nullable();   // stored in storage/app/public/news/
    $table->string('format')->default('jpg');   // jpg | png
    $table->longText('body')->nullable();       // full article content
    $table->string('external_url')->nullable(); // link to external source if any
    $table->integer('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 4.24 contact_messages table

```php
Schema::create('contact_messages', function (Blueprint $table) {
    $table->id();
    $table->string('first_name');
    $table->string('email');
    $table->string('phone')->nullable();
    $table->text('message');
    $table->boolean('is_read')->default(false);
    $table->timestamps();
});
```

### 4.25 pages table

```php
// CMS pages: privacy, terms, cookies
Schema::create('pages', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();   // privacy | terms | cookies
    $table->longText('content');        // HTML or Markdown
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

---

## 5. Eloquent Models

### 5.1 Admin Model

```php
// app/Models/Admin.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];
}
```

### 5.2 SiteSetting Model

```php
// app/Models/SiteSetting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name', 'meta_title', 'meta_description', 'logo_path',
        'primary_phone', 'primary_email', 'address', 'business_hours',
        'footer_description', 'footer_cta_title', 'footer_cta_text',
        'footer_cta_button_label', 'footer_cta_button_href',
        'map_embed_url', 'copyright_text', 'office_image_path',
        'company_presentation_file', 'how_we_work_video_url',
    ];

    // Helper to return the public URL of the logo
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : null;
    }

    public function getOfficeImageUrlAttribute(): ?string
    {
        return $this->office_image_path
            ? asset('storage/' . $this->office_image_path)
            : null;
    }

    public function getPresentationUrlAttribute(): ?string
    {
        return $this->company_presentation_file
            ? asset('storage/' . $this->company_presentation_file)
            : null;
    }
}
```

### 5.3 HeroSlide Model

```php
// app/Models/HeroSlide.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = ['image_path', 'alt_text', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
```

### 5.4 Service Model

```php
// app/Models/Service.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'service_category_id', 'title', 'price',
        'description', 'badge', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
```

### 5.5 CaseStudy Model

```php
// app/Models/CaseStudy.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStudy extends Model
{
    protected $fillable = [
        'case_study_category_id', 'title', 'slug',
        'image_path', 'summary', 'body', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected $appends = ['image_url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CaseStudyCategory::class, 'case_study_category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
```

The pattern for `image_url` accessors and `scopeActive` scopes repeats on every model that has images or an `is_active` column. The remaining models (Testimonial, TeamMember, Achievement, Partner, NewsPost, etc.) follow exactly the same pattern. Their `$fillable` arrays match their migration columns exactly.

---

## 6. Database Seeders

All static data currently hard-coded in the frontend is seeded here so the application starts with real content after `php artisan db:seed`.

### 6.1 DatabaseSeeder

```php
// database/seeders/DatabaseSeeder.php
public function run(): void
{
    $this->call([
        AdminSeeder::class,
        SiteSettingSeeder::class,
        SocialLinkSeeder::class,
        NavItemSeeder::class,
        HeroSettingSeeder::class,
        HeroSlideSeeder::class,
        ServiceCategorySeeder::class,
        ServiceSeeder::class,
        CaseStudyCategorySeeder::class,
        CaseStudySeeder::class,
        TestimonialSeeder::class,
        AboutSettingSeeder::class,
        TimelineMilestoneSeeder::class,
        MissionBulletSeeder::class,
        ApproachCardSeeder::class,
        AchievementSeeder::class,
        PartnerSeeder::class,
        TeamMemberSeeder::class,
        FaqSeeder::class,
        ContactDepartmentSeeder::class,
        PublicationSeeder::class,
        FormTemplateSeeder::class,
        NewsPostSeeder::class,
        PageSeeder::class,
    ]);
}
```

### 6.2 AdminSeeder

```php
Admin::create([
    'name'     => 'Greenland Admin',
    'email'    => env('ADMIN_EMAIL', 'admin@greenlandcompliance.com'),
    'password' => env('ADMIN_PASSWORD', 'Admin@1234'),
]);
```

### 6.3 SiteSettingSeeder

```php
SiteSetting::create([
    'site_name'          => 'Greenland Business & Compliance',
    'meta_title'         => 'Greenland Business & Compliance',
    'meta_description'   => 'Professional Advisory, Accounting, and Regulatory solutions in Bangladesh',
    'primary_phone'      => '+8801987-644603',
    'primary_email'      => 'contact@greenlandcompliance.com',
    'address'            => 'Bottola Bazar, Bhakurta, Savar, Dhaka-1313, Bangladesh.',
    'business_hours'     => 'Mon to Sat 8 am to 10 pm | Sunday CLOSED',
    'footer_description' => 'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. Building sustainable business foundations in Bangladesh since 1985.',
    'footer_cta_title'   => 'Ready to take your business to the next level?',
    'footer_cta_text'    => 'Our expert consultants are ready to help you navigate the complexities of compliance and growth in Bangladesh.',
    'footer_cta_button_label' => 'Request a Free Quote',
    'footer_cta_button_href'  => '/contact',
    'copyright_text'     => '© ' . date('Y') . ' Greenland Business & Compliance. All Rights Reserved.',
    'map_embed_url'      => 'https://www.google.com/maps/embed?pb=!1m18!...',
    'how_we_work_video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
]);
```

### 6.4 SocialLinkSeeder

```php
$links = [
    ['platform' => 'linkedin',  'url' => '#', 'sort_order' => 1],
    ['platform' => 'facebook',  'url' => '#', 'sort_order' => 2],
    ['platform' => 'twitter',   'url' => '#', 'sort_order' => 3],
    ['platform' => 'instagram', 'url' => '#', 'sort_order' => 4],
];
foreach ($links as $link) {
    SocialLink::create($link + ['is_active' => true]);
}
```

### 6.5 NavItemSeeder

```php
// Header navigation
$header = [
    ['label' => 'Home',         'href' => '/',             'location' => 'header',          'sort_order' => 1],
    ['label' => 'Services',     'href' => '/services',     'location' => 'header',          'sort_order' => 2],
    ['label' => 'Case Studies', 'href' => '/case-studies', 'location' => 'header',          'sort_order' => 3],
    ['label' => 'About Us',     'href' => '/about',        'location' => 'header',          'sort_order' => 4],
    ['label' => 'Contact US',   'href' => '/contact',      'location' => 'header',          'sort_order' => 5],
    ['label' => 'Resources',    'href' => '/resources',    'location' => 'header',          'sort_order' => 6],
    // Footer quick links
    ['label' => 'Home',         'href' => '/',             'location' => 'footer_quick',    'sort_order' => 1],
    ['label' => 'About Us',     'href' => '/about',        'location' => 'footer_quick',    'sort_order' => 2],
    ['label' => 'Our Services', 'href' => '/services',     'location' => 'footer_quick',    'sort_order' => 3],
    ['label' => 'Case Studies', 'href' => '/case-studies', 'location' => 'footer_quick',    'sort_order' => 4],
    ['label' => 'Resources',    'href' => '/resources',    'location' => 'footer_quick',    'sort_order' => 5],
    ['label' => 'Contact Us',   'href' => '/contact',      'location' => 'footer_quick',    'sort_order' => 6],
    // Footer services links
    ['label' => 'Business Advisory',       'href' => '/services', 'location' => 'footer_services', 'sort_order' => 1],
    ['label' => 'Audit & Assurance',       'href' => '/services', 'location' => 'footer_services', 'sort_order' => 2],
    ['label' => 'Taxation Services',       'href' => '/services', 'location' => 'footer_services', 'sort_order' => 3],
    ['label' => 'Regulatory Compliance',   'href' => '/services', 'location' => 'footer_services', 'sort_order' => 4],
    ['label' => 'Human Capital Management','href' => '/services', 'location' => 'footer_services', 'sort_order' => 5],
    ['label' => 'Strategy Consulting',     'href' => '/services', 'location' => 'footer_services', 'sort_order' => 6],
    // Footer policy links
    ['label' => 'Privacy Policy',    'href' => '/privacy', 'location' => 'footer_policy', 'sort_order' => 1],
    ['label' => 'Terms of Service',  'href' => '/terms',   'location' => 'footer_policy', 'sort_order' => 2],
    ['label' => 'Cookie Settings',   'href' => '/cookies', 'location' => 'footer_policy', 'sort_order' => 3],
];
foreach ($header as $item) {
    NavItem::create($item + ['is_active' => true]);
}
```

### 6.6 HeroSettingSeeder

```php
HeroSetting::create([
    'headline_line1' => 'Your Vision, Our Compliance.',
    'headline_line2' => 'Building Sustainable Business Foundations in Bangladesh',
    'paragraph'      => 'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. We handle the complexity so you can lead with confidence.',
    'cta1_label'     => 'Book a Consultation',
    'cta1_href'      => '/contact',
    'cta2_label'     => 'Explore Our Services',
    'cta2_href'      => '/services',
]);
```

### 6.7 HeroSlideSeeder

The actual hero image files are large (`hero1.jpg` is about 22.8 MB and `hero2..jpg` is about 10.7 MB). Copy the initial files directly into storage during migration, then optimize/compress replacement hero images before admin upload. If the admin must accept originals above 5 MB, raise the documented `max:5120` validation limit and PHP upload limits together; otherwise keep `max:5120` and require optimized web images. Until storage URLs are confirmed, the frontend falls back to local transition assets gracefully (see frontend dynamic doc).

```php
$slides = [
    ['image_path' => 'hero/hero1.jpg',   'alt_text' => 'Hero slide 1', 'sort_order' => 1],
    ['image_path' => 'hero/hero2..jpg',  'alt_text' => 'Hero slide 2', 'sort_order' => 2],
    ['image_path' => 'hero/hero3.jpg',   'alt_text' => 'Hero slide 3', 'sort_order' => 3],
];
foreach ($slides as $s) {
    HeroSlide::create($s + ['is_active' => true]);
}
```

Important: the original images must be physically copied from `frontend/public/Background Img/` to `backend/storage/app/public/hero/` during the initial migration. The admin panel will then allow replacing them with properly optimised files.

### 6.8 ServiceCategorySeeder

```php
$categories = [
    ['label' => 'Advisory',                'slug' => 'advisory',             'sort_order' => 1],
    ['label' => 'Audit',                   'slug' => 'audit',                'sort_order' => 2],
    ['label' => 'Consulting',              'slug' => 'consulting',           'sort_order' => 3],
    ['label' => 'Human Capital',           'slug' => 'human capital',        'sort_order' => 4],
    ['label' => 'Mergers & Acquisitions',  'slug' => 'mergers & acquisitions','sort_order' => 5],
    ['label' => 'Operations',              'slug' => 'operations',           'sort_order' => 6],
    ['label' => 'Regulatory',              'slug' => 'regulatory',           'sort_order' => 7],
    ['label' => 'Strategy',               'slug' => 'strategy',             'sort_order' => 8],
    ['label' => 'Tax',                     'slug' => 'tax',                  'sort_order' => 9],
];
```

### 6.9 ServiceSeeder

Advisory and Audit services are seeded with the exact static data from the frontend inventory. Stubs are created for the remaining seven categories.

```php
// Advisory (category id 1)
$advisory = [
    ['title' => 'Financial Services',                      'price' => '$75',  'badge' => 'NEW',     'sort_order' => 1, 'description' => 'Companies dislike the term \'turnaround consulting\' because it represents failure. The truth is that turnaround consulting represents success.'],
    ['title' => 'Strategic planning',                      'price' => '$60',  'badge' => null,      'sort_order' => 2, 'description' => 'Bonds and commodities are much more stable than stocks and trades. We allow our clients to invest in the right bonds & commodities.'],
    ['title' => 'Audit & Assurance',                       'price' => '$115', 'badge' => 'SPECIAL', 'sort_order' => 3, 'description' => 'Audit and assurance is all about meticulous data analysis. Everything needs to be checked, double checked, and triple checked.'],
    ['title' => 'Trades & Stocks',                         'price' => '$57',  'badge' => null,      'sort_order' => 4, 'description' => 'This allows us to specialize in all dimensions of trades and stocks, because we have a specialist within the team for every scenario.'],
    ['title' => 'Strategic Planning',                      'price' => '$35',  'badge' => 'NEW',     'sort_order' => 5, 'description' => 'We work with our clients and do a deep analysis of their business. We help prepare possible outcomes to different decisions.'],
    ['title' => 'Financial Projections',                   'price' => '$80',  'badge' => null,      'sort_order' => 6, 'description' => 'This stops companies from taking drastic measures like downsizing or closing down sites; those things happen only with no.'],
    ['title' => 'International Business Opportunities',    'price' => '$58',  'badge' => null,      'sort_order' => 7, 'description' => 'We allow you to enter international waters without having to worry about making a mistake, as experience.'],
    ['title' => 'Business Planning, Strategy & Execution', 'price' => '$49',  'badge' => 'NEW',     'sort_order' => 8, 'description' => 'Execution is the single most important part of the whole process, poor execution can result in a lot of lost time and money.'],
];
$advisoryCat = ServiceCategory::where('slug', 'advisory')->first();
foreach ($advisory as $item) {
    Service::create($item + ['service_category_id' => $advisoryCat->id, 'is_active' => true]);
}

// Audit (category id 2)
$audit = [
    ['title' => 'External Audit',          'price' => '$200', 'badge' => 'NEW',  'sort_order' => 1, 'description' => 'Comprehensive external auditing services for large corporations and SMEs to ensure regulatory compliance.'],
    ['title' => 'Internal Control Review', 'price' => '$150', 'badge' => null,   'sort_order' => 2, 'description' => 'Evaluation and improvement of your internal control systems to mitigate risks and enhance operational efficiency.'],
];
$auditCat = ServiceCategory::where('slug', 'audit')->first();
foreach ($audit as $item) {
    Service::create($item + ['service_category_id' => $auditCat->id, 'is_active' => true]);
}
// Remaining 7 categories get placeholder services that the admin can replace.
```

### 6.10 CaseStudySeeder

```php
$categoryMap = [
    'Business Services'              => ['slug' => 'business-services'],
    'Travel & Aviation'              => ['slug' => 'travel-aviation'],
    'Energy & Environment'           => ['slug' => 'energy-environment'],
    'Financial Services'             => ['slug' => 'financial-services'],
    'Surface Transport & Logistics'  => ['slug' => 'surface-transport-logistics'],
    'Consumer Products'              => ['slug' => 'consumer-products'],
];
// Create each category first, then seed case studies referencing them.

$caseStudies = [
    ['category' => 'Business Services',             'title' => 'Healthcare giant overcomes merger in 2015',           'sort_order' => 1],
    ['category' => 'Travel & Aviation',             'title' => 'Focus on core delivers growth for retailer trading',  'sort_order' => 2],
    ['category' => 'Business Services',             'title' => 'Transformation sparks financial income for all',      'sort_order' => 3],
    ['category' => 'Business Services',             'title' => 'Increased sales productivity frees selling time and saves millions', 'sort_order' => 4],
    ['category' => 'Energy & Environment',          'title' => 'Constructing a best-in-class global procurement',    'sort_order' => 5],
    ['category' => 'Business Services',             'title' => 'Turning around a reactive pharma supply chain',      'sort_order' => 6],
    ['category' => 'Financial Services',            'title' => 'Leading consumer products companies',                'sort_order' => 7],
    ['category' => 'Surface Transport & Logistics', 'title' => 'Bain helps transportation & logistics companies',    'sort_order' => 8],
    ['category' => 'Energy & Environment',          'title' => 'Developing a strategy and roadmap for clients',      'sort_order' => 9],
    ['category' => 'Business Services',             'title' => 'Constructing the best-in-class global',             'sort_order' => 10],
    ['category' => 'Surface Transport & Logistics', 'title' => 'Demand as transportation services as',              'sort_order' => 11],
    ['category' => 'Consumer Products',             'title' => 'Pricing games: A technology company',               'sort_order' => 12],
];
```

### 6.11 TestimonialSeeder

```php
$testimonials = [
    [
        'author'      => 'Damian Smulders',
        'role'        => 'CEO, TechFlow',
        'quote'       => 'The results were clear, professional, and persuasive, and the investors and advisors who have seen the materials loved them. They know what investors want.',
        'avatar_path' => 'about/avatar1.png',
        'page'        => 'global',
        'sort_order'  => 1,
    ],
    [
        'author'      => 'Cintia Le Cane',
        'role'        => 'Chairman, Harmony Corporation',
        'quote'       => 'We thought a lot before choosing our compliance partner because we wanted to be sure our investment would yield results. Greenland Compliance is an invaluable partner.',
        'avatar_path' => 'about/avatar2.png',
        'page'        => 'global',
        'sort_order'  => 2,
    ],
    [
        'author'      => 'Amanda Seyford',
        'role'        => 'Founder & CEO, Arcade Systems',
        'quote'       => 'We were amazed by how little effort was required on our part to have Greenland prepare these materials. We exchanged a few phone calls. An invaluable partner.',
        'avatar_path' => 'about/avatar3.png',
        'page'        => 'global',
        'sort_order'  => 3,
    ],
];
```

Note: The avatar images must be copied from `frontend/public/about/avatar1.png` etc. to `backend/storage/app/public/about/`.

### 6.12 AboutSettingSeeder

```php
AboutSetting::create([
    'banner_label'       => 'About Us',
    'hero_heading_line1' => 'Workshops',
    'hero_heading_line2' => 'that awesome!',
    'hero_paragraph'     => 'We are a company that offers design and build services for you from initial sketches to the final construction.',
    'hero_image_path'    => 'about/hero.png',
    'hero_cta_label'     => 'get a quote',
    'hero_cta_href'      => '/contact',
    'overview_paragraph1'=> 'Greenland Business & Compliance is a leading advisory powerhouse in Bangladesh. We began our operations a few decades ago and have grown due to excellent relationships with our clients.',
    'overview_paragraph2'=> 'We achieved our success because of how successfully we integrate with our clients. One complaint many people have about consultants is that they can be disruptive — our clients face no such issues.',
    'overview_callout'   => 'Greenland continues to grow every day thanks to the confidence our clients have in us. We cover many industries such as financial, energy, business services, and consumer products.',
    'mission_heading'    => 'Our mission',
    'mission_intro'      => 'Our renowned coaching programs will allow you to:',
    'approach_intro1'    => 'Greenland Business & Compliance approaches every client\'s business as if it were our own.',
    'approach_intro2'    => 'The right approach is necessary for the right outcome. We know that in order to maximize the potential of success for your company we need to shape our expert advice in a way that applies to your way of doing business.',
    'footer_cta_text'    => 'LOOKING FOR A FIRST-CLASS BUSINESS PLAN CONSULTANT?',
    'footer_cta_button_label' => 'get a quote',
    'footer_cta_button_href'  => '/contact',
]);
```

### 6.13 TimelineMilestoneSeeder

```php
$milestones = [
    ['year' => '1985', 'title' => 'Start with a small service',   'sort_order' => 1, 'description' => 'This was the year when we started our company. We had no idea how far we would go, we weren\'t even sure that we would be able to survive for a few years. What drove us to start the company was the understanding that we could provide a service no one else was providing.'],
    ['year' => '1990', 'title' => 'First employees',              'sort_order' => 2, 'description' => 'This was the first period when Greenland Business & Compliance actually felt like it would stick around for a while. We realized we were growing more stable and expanding in the same area. We needed a new office as we had severely outgrown the last one.'],
    ['year' => '2001', 'title' => 'First recognition',            'sort_order' => 3, 'description' => 'By this time we were a well-known name within the industry. We had been prominent members of the industry for more than 16 years, and worked for some of the biggest clients in the industry.'],
    ['year' => '2015', 'title' => 'Greenland — corporation or family', 'sort_order' => 4, 'description' => 'Our journey has only brought us higher. Information Technology completely changes the way we analyze and present data. We have embraced new technologies and have ensured that our clients receive cutting edge analytics.'],
];
```

### 6.14 MissionBulletSeeder

```php
$bullets = [
    ['text' => 'Work fewer hours — and make more money',                                         'sort_order' => 1],
    ['text' => 'Attract and retain quality, high-paying customers',                              'sort_order' => 2],
    ['text' => 'Manage your time so you\'ll get more done in less time',                         'sort_order' => 3],
    ['text' => 'Hone sharp leadership skills to manage your team',                               'sort_order' => 4],
    ['text' => 'Cut expenses without sacrificing quality',                                       'sort_order' => 5],
    ['text' => 'Automate your business, so you can leave for days, weeks, or even months',       'sort_order' => 6],
];
```

### 6.15 ApproachCardSeeder

```php
$cards = [
    ['title' => 'Travel and Aviation Consulting',   'icon' => 'Plane',        'sort_order' => 1, 'description' => 'Armed with statistical knowledge, technical expertise, and fact based prediction, we allow your business to truly soar.'],
    ['title' => 'Business Services Consulting',     'icon' => 'TrendingUp',   'sort_order' => 2, 'description' => 'We help you shape and position your business services in a way that enhances the output of your clients.'],
    ['title' => 'Consumer Products Consulting',     'icon' => 'ShoppingCart', 'sort_order' => 3, 'description' => 'We help companies dealing in consumer products create and present products that perfectly blend in with the zeitgeist.'],
    ['title' => 'Financial Services Consulting',    'icon' => 'Building2',    'sort_order' => 4, 'description' => 'Our financial experts help you analyze financial data, to create a rock steady financial foundation.'],
    ['title' => 'Energy and Environment Consulting','icon' => 'Zap',          'sort_order' => 5, 'description' => 'We work with energy companies to increase their efficiency and eliminate any environmentally harmful practices.'],
    ['title' => 'TAX Services Consulting',          'icon' => 'Truck',        'sort_order' => 6, 'description' => 'We are a company that offers design and build services for you from initial sketches to the final construction.'],
];
```

### 6.16 AchievementSeeder

```php
$achievements = [
    ['title' => 'Certificate of Achievement',   'image_path' => 'achievements/cert1.png', 'sort_order' => 1],
    ['title' => 'Certificate of Recognition',   'image_path' => 'achievements/cert2.png', 'sort_order' => 2],
    ['title' => 'Certificate of Excellence',    'image_path' => 'achievements/cert3.png', 'sort_order' => 3],
    ['title' => 'Certificate of Incorporation', 'image_path' => 'achievements/cert4.png', 'sort_order' => 4],
];
// Note: these image files are missing in the original frontend; the admin must upload them.
```

### 6.17 PartnerSeeder

```php
$partners = [
    ['name' => 'Aramiz Company',  'industry' => 'Athletic Performance Tracking Devices', 'location' => 'Escondido, CA',     'sort_order' => 1, 'description' => 'We aren\'t such an agile and dependable organization just because of our own team; we also have a fantastic network of partners who compliment our services.'],
    ['name' => 'Adup Media LLC',  'industry' => 'Media & Marketing Consulting',          'location' => 'Walnut Creek, CA',  'sort_order' => 2, 'description' => 'Our partners are the top companies in their own respective industries, and are known to deliver high quality services and products.'],
    ['name' => 'Green Shield',    'industry' => 'Heart Transplant Monitoring Technology','location' => 'Charlotte, NC',     'sort_order' => 3, 'description' => 'Strategic partnerships allow companies to expand and specialize without limitations. Instead of spending money perfecting a new thing, we prefer to perfect our own services.'],
    ['name' => 'Primo Software',  'industry' => 'Software Development',                  'location' => 'Manitowoc, WI',    'sort_order' => 4, 'description' => 'Our customers trust us so much that they often come to us with problems beyond the scope of financial consultancy.'],
];
```

### 6.18 TeamMemberSeeder

```php
$team = [
    ['name' => 'Brandon Copperfield', 'role' => 'Founder & CEO',           'sort_order' => 1, 'description' => 'The founder of Greenland Business & Compliance, he has been the captain of this ship from the beginning and has sailed it to great heights.'],
    ['name' => 'Clark Roberts',       'role' => 'Chief Finance Officer',   'sort_order' => 2, 'description' => 'Being the CFO in the Financial Industry is a tough task, thankfully he was here to man the helm.'],
    ['name' => 'Ashley Hardy',        'role' => 'VP Sales and Marketing',  'sort_order' => 3, 'description' => 'She is an accomplished business developer. Her skills at creating relationships with clients are unparalleled.'],
    ['name' => 'Dennis Norris',       'role' => 'Chief Marketing Officer', 'sort_order' => 4, 'description' => 'He has helped Greenland reach new heights and enter new markets. His skills of understanding audiences are exceptional.'],
    ['name' => 'Gina Kennedy',        'role' => 'Administrator',           'sort_order' => 5, 'description' => 'As we help other companies grow, she helps us grow. She handles all the internal work at Greenland with efficiency.'],
    ['name' => 'Fernando Torres',     'role' => 'Tax Consultant',          'sort_order' => 6, 'description' => 'Tax laws and regulations are some of the most complicated and infuriating parts of the financial world — he navigates them masterfully.'],
];
```

### 6.19 FaqSeeder

```php
$faqs = [
    ['question' => 'How many times do I have to tell you a few ways?',            'sort_order' => 1, 'answer' => 'Progressively generate synergistic total linkage through cross-media intellectual capital. Enthusiastically parallel task team building e-tailers without standards compliant initiatives.'],
    ['question' => 'What is do I have to tell you a few lorem?',                  'sort_order' => 2, 'answer' => 'Greenland Business & Compliance continues to grow every day thanks to the confidence our clients have in us. We cover many industries such as financial, energy, business services, and consumer products.'],
    ['question' => 'I have a technical problem I need resolved, who do I email?', 'sort_order' => 3, 'answer' => 'Please contact our technical support team at support@greenlandcompliance.com for any technical inquiries or issues.'],
    ['question' => 'What other services are you compatible with?',                'sort_order' => 4, 'answer' => 'Our systems are designed to be highly compatible with modern enterprise software including SAP, Oracle, and Microsoft Dynamics.'],
    ['question' => 'How many times do I have to tell you a few ways?',            'sort_order' => 5, 'answer' => 'This is another example of a frequently asked question to demonstrate the accordion functionality.'],
    ['question' => 'What other services are you compatible with?',                'sort_order' => 6, 'answer' => 'We offer full integration services for a wide range of industry-standard tools.'],
];
```

### 6.20 ContactDepartmentSeeder

```php
$depts = [
    ['title' => 'Any Queries',      'email' => 'contact@greenlandcompliance.com', 'sort_order' => 1],
    ['title' => 'Help or Support',  'email' => 'help@greenlandcompliance.com',    'sort_order' => 2],
    ['title' => 'Job or Career',    'email' => 'career@greenlandcompliance.com',  'sort_order' => 3],
];
```

### 6.21 PublicationSeeder

```php
$publications = [
    ['title' => 'Government Gazette on Labor Law 2023',  'format' => 'pdf',  'category' => 'Gazette',    'sort_order' => 1],
    ['title' => 'Company Compliance Timeline 2024',      'format' => 'jpg',  'category' => 'Timeline',   'sort_order' => 2],
    ['title' => 'Industrial Safety Guidelines',          'format' => 'pdf',  'category' => 'Nirdeshika', 'sort_order' => 3],
    ['title' => 'Business Ethics & Conduct Book',        'format' => 'pdf',  'category' => 'Books',      'sort_order' => 4],
    ['title' => 'Environmental Regulations Handbook',    'format' => 'word', 'category' => 'Manual',     'sort_order' => 5],
    ['title' => 'Taxation Policy Update',                'format' => 'pdf',  'category' => 'Govt. Gazette', 'sort_order' => 6],
];
```

### 6.22 FormTemplateSeeder

```php
$forms = [
    // Human Resources
    ['title' => 'Employee Onboarding Form',      'category_group' => 'Human Resources',    'format' => 'word',  'language' => 'English', 'sort_order' => 1],
    ['title' => 'Leave Application Template',    'category_group' => 'Human Resources',    'format' => 'excel', 'language' => 'Bangla',  'sort_order' => 2],
    ['title' => 'Performance Review Template',   'category_group' => 'Human Resources',    'format' => 'word',  'language' => 'English', 'sort_order' => 3],
    // Legal & Compliance
    ['title' => 'Trade License Renewal Form',    'category_group' => 'Legal & Compliance', 'format' => 'word',  'language' => 'Bangla',  'sort_order' => 4],
    ['title' => 'Compliance Audit Checklist',    'category_group' => 'Legal & Compliance', 'format' => 'excel', 'language' => 'English', 'sort_order' => 5],
    ['title' => 'Annual Return Template',        'category_group' => 'Legal & Compliance', 'format' => 'excel', 'language' => 'Bangla',  'sort_order' => 6],
    // Finance & Accounts
    ['title' => 'Expense Claim Form',            'category_group' => 'Finance & Accounts', 'format' => 'excel', 'language' => 'English', 'sort_order' => 7],
    ['title' => 'Tax Deduction Statement',       'category_group' => 'Finance & Accounts', 'format' => 'excel', 'language' => 'Bangla',  'sort_order' => 8],
];
```

### 6.23 NewsPostSeeder

```php
$news = [
    ['title' => 'Greenland Compliance joins Global Safety Summit',       'category' => 'Events',     'published_at' => '2026-05-10', 'format' => 'jpg', 'sort_order' => 1],
    ['title' => 'New Labor Law Amendments: What you need to know',       'category' => 'Regulatory', 'published_at' => '2026-05-08', 'format' => 'png', 'sort_order' => 2],
    ['title' => 'Annual General Meeting Highlights 2025',                'category' => 'Corporate',  'published_at' => '2026-04-25', 'format' => 'jpg', 'sort_order' => 3],
    ['title' => 'Excellence in Compliance Award Won',                    'category' => 'Awards',     'published_at' => '2026-04-20', 'format' => 'png', 'sort_order' => 4],
];
```

### 6.24 PageSeeder

```php
$pages = [
    ['slug' => 'privacy', 'title' => 'Privacy Policy',  'content' => '<p>Privacy policy content goes here.</p>'],
    ['slug' => 'terms',   'title' => 'Terms of Service', 'content' => '<p>Terms of service content goes here.</p>'],
    ['slug' => 'cookies', 'title' => 'Cookie Settings',  'content' => '<p>Cookie settings information goes here.</p>'],
];
```

---

## 7. Authentication

### 7.1 Guard Configuration

Add the `admin` guard to `config/auth.php`:

```php
'guards' => [
    'web' => [
        'driver'   => 'session',
        'provider' => 'users',
    ],
    'admin' => [
        'driver'   => 'session',
        'provider' => 'admins',  // custom provider
    ],
],

'providers' => [
    'users'  => ['driver' => 'eloquent', 'model' => App\Models\User::class],
    'admins' => ['driver' => 'eloquent', 'model' => App\Models\Admin::class],
],
```

### 7.2 LoginController

```php
// app/Http/Controllers/Admin/Auth/LoginController.php

public function showLogin()
{
    return view('admin.auth.login');
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
}

public function logout(Request $request)
{
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin.login');
}
```

---

## 8. Routes

### 8.1 Web Routes (Admin Panel)

The admin panel is exposed at `ADMIN_PANEL_URL`. If `ADMIN_PANEL_URL` is `<BACKEND_URL>/admin`, keep the `/admin` prefix below. If deployment uses a dedicated admin subdomain, route that subdomain to these same routes and keep the Laravel route names unchanged.

```php
// routes/web.php

// Auth routes (guest only)
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login',  [LoginController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.post');
});

// Admin panel routes (auth required)
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Site settings (single record — edit only, no index/destroy)
    Route::get('/site-settings',        [SiteSettingsController::class, 'edit'])->name('site-settings.edit');
    Route::put('/site-settings',        [SiteSettingsController::class, 'update'])->name('site-settings.update');

    // Hero settings
    Route::get('/hero-settings',        [HeroSettingsController::class, 'edit'])->name('hero-settings.edit');
    Route::put('/hero-settings',        [HeroSettingsController::class, 'update'])->name('hero-settings.update');

    // Full CRUD resources
    Route::resource('hero-slides',          HeroSlideController::class);
    Route::resource('nav-items',            NavItemController::class);
    Route::resource('social-links',         SocialLinkController::class);
    Route::resource('service-categories',   ServiceCategoryController::class);
    Route::resource('services',             ServiceController::class);
    Route::resource('case-study-categories',CaseStudyCategoryController::class);
    Route::resource('case-studies',         CaseStudyController::class);
    Route::resource('testimonials',         TestimonialController::class);
    Route::resource('timeline-milestones',  TimelineMilestoneController::class);
    Route::resource('mission-bullets',      MissionBulletController::class);
    Route::resource('approach-cards',       ApproachCardController::class);
    Route::resource('achievements',         AchievementController::class);
    Route::resource('partners',             PartnerController::class);
    Route::resource('team-members',         TeamMemberController::class);
    Route::resource('faqs',                 FaqController::class);
    Route::resource('contact-departments',  ContactDepartmentController::class);
    Route::resource('publications',         PublicationController::class);
    Route::resource('form-templates',       FormTemplateController::class);
    Route::resource('news-posts',           NewsPostController::class);
    Route::resource('pages',                PageController::class);

    // About settings (single record)
    Route::get('/about-settings',       [AboutSettingsController::class, 'edit'])->name('about-settings.edit');
    Route::put('/about-settings',       [AboutSettingsController::class, 'update'])->name('about-settings.update');

    // Contact messages (read-only for admin; created via API)
    Route::get('/contact-messages',         [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{id}',    [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::patch('/contact-messages/{id}/read', [ContactMessageController::class, 'markRead'])->name('contact-messages.markRead');
    Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // Sort order AJAX endpoints
    Route::post('/reorder/{model}', [ReorderController::class, 'update'])->name('reorder');
});
```

### 8.2 API Routes

```php
// routes/api.php
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Public site-wide data
    Route::get('/site',        [SiteController::class, 'index']);
    Route::get('/navigation',  [NavigationController::class, 'index']);

    // Hero
    Route::get('/hero',        [HeroController::class, 'index']);

    // Services
    Route::get('/services',                    [ServiceController::class, 'index']);
    Route::get('/services/categories',         [ServiceController::class, 'categories']);

    // Case studies
    Route::get('/case-studies',                 [CaseStudyController::class, 'index']);
    Route::get('/case-studies/categories',      [CaseStudyController::class, 'categories']);
    Route::get('/case-studies/{slug}',          [CaseStudyController::class, 'show']);

    // About
    Route::get('/about',                        [AboutController::class, 'index']);

    // Contact
    Route::get('/contact',                      [ContactController::class, 'info']);
    Route::post('/contact',                     [ContactController::class, 'submit']);

    // Resources
    Route::get('/resources/publications',       [ResourceController::class, 'publications']);
    Route::get('/resources/forms',              [ResourceController::class, 'forms']);
    Route::get('/resources/news',               [ResourceController::class, 'news']);

    // Testimonials
    Route::get('/testimonials',                 [TestimonialController::class, 'index']);

    // Pages (privacy/terms/cookies)
    Route::get('/pages/{slug}',                 [PageController::class, 'show']);
});
```

---

## 9. Admin CRUD To Frontend Coverage

The Blade admin panel is the write surface for site content. The Next.js frontend is not expected to call admin CRUD routes; it consumes the public REST API shapes documented in `API.md`. Every admin-managed resource below must either feed a public frontend section or be marked admin-only.

| Admin resource | Admin capability | Frontend/API consumer |
| --- | --- | --- |
| Site settings | Edit single record | `GET /site`, layout metadata, Navbar, Footer, Contact, About video/presentation |
| Hero settings | Edit single record | `GET /hero`, Home hero copy and CTAs |
| Hero slides | Full CRUD + upload + order | `GET /hero`, Home hero slider |
| Nav items | Full CRUD + order | `GET /navigation`, Navbar and Footer links |
| Social links | Full CRUD + order | `GET /site` and `GET /contact`, Footer and Contact social icons |
| Service categories | Full CRUD + order | `GET /services`, Services tabs |
| Services | Full CRUD + order | `GET /services`, Services cards |
| Case study categories | Full CRUD + order | `GET /case-studies`, case study category labels; `GET /case-studies/categories` reserved for future filters/admin previews |
| Case studies | Full CRUD + upload + order | `GET /case-studies` and `GET /case-studies/{slug}` |
| Testimonials | Full CRUD + avatar upload + order | bundled in `GET /about`; standalone `GET /testimonials?page=case_studies` |
| About settings | Edit single record | `GET /about`, About hero, overview, approach intro, About footer CTA |
| Timeline milestones | Full CRUD + order | `GET /about`, Overview timeline |
| Mission bullets | Full CRUD + order | `GET /about`, Mission list |
| Approach cards | Full CRUD + order | `GET /about`, Approach tab |
| Achievements | Full CRUD + upload + order | `GET /about`, Achievement tab |
| Partners | Full CRUD + optional logo upload + order | `GET /about`, Partners tab |
| Team members | Full CRUD + optional image upload + order | `GET /about`, Team tab |
| FAQs | Full CRUD + order | `GET /about`, FAQ tab |
| Contact departments | Full CRUD + order | `GET /contact`, Contact department sidebar |
| Publications | Full CRUD + file upload + order | `GET /resources/publications`, Resources publications tab |
| Form templates | Full CRUD + file upload + order | `GET /resources/forms`, Resources forms tab |
| News posts | Full CRUD + image/upload/link + order | `GET /resources/news`, Resources news tab |
| Contact messages | Read, mark read, delete | Created by `POST /contact`; visible only in admin inbox |
| Pages | Full CRUD for `privacy`, `terms`, `cookies` | `GET /pages/{slug}`, policy pages |
| Reorder endpoint | AJAX order updates | Admin-only helper; affects public endpoint ordering |

CRUD support is complete when each full CRUD resource has index/create/store/edit/update/destroy screens, validation, active status where documented, sort order where documented, and media/file deletion on replacement. Single-record settings screens are intentionally edit/update only.

---

## 10. Admin Panel UI

### 10.1 Master Layout

The file `resources/views/layouts/admin.blade.php` wraps every admin page. It loads the compiled Tailwind CSS v4 admin bundle through Laravel Vite and Alpine.js from CDN, then provides the sidebar navigation, top bar, and main content area.

```blade
<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Greenland Compliance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex">

    {{-- Sidebar --}}
    <aside
        :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="bg-secondary text-white flex-shrink-0 transition-all duration-300 flex flex-col"
    >
        {{-- Logo --}}
        <div class="p-4 border-b border-gray-700 flex items-center gap-3">
            <img src="{{ asset('storage/' . optional(App\Models\SiteSetting::first())->logo_path) }}"
                 alt="Logo" class="h-8 object-contain brightness-0 invert"
                 x-show="sidebarOpen">
            <span class="font-bold text-sm" x-show="sidebarOpen">Admin Panel</span>
        </div>

        {{-- Navigation groups --}}
        <nav class="flex-1 overflow-y-auto py-4">
            @include('admin.partials.sidebar-nav')
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-gray-700">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left text-sm text-gray-400 hover:text-white flex items-center gap-2">
                    <svg ...> <!-- logout icon --> </svg>
                    <span x-show="sidebarOpen">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main area --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-800">
                <!-- hamburger icon -->
            </button>
            <h1 class="text-lg font-semibold text-gray-700">@yield('page-title')</h1>
            <div class="ml-auto text-sm text-gray-500">
                {{ Auth::guard('admin')->user()->name }}
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
```

### 10.2 Sidebar Navigation Groups

The sidebar groups admin resources logically. This is rendered by `admin.partials.sidebar-nav`.

```
Site
  ├── Dashboard
  ├── Site Settings
  └── Social Links

Homepage
  ├── Hero Slides
  └── Hero Settings

Navigation
  └── Nav Items

Services
  ├── Service Categories
  └── Services

Case Studies
  ├── Case Study Categories
  └── Case Studies

About
  ├── About Settings
  ├── Timeline Milestones
  ├── Mission Bullets
  ├── Approach Cards
  ├── Achievements
  ├── Partners
  └── Team Members

Engagement
  ├── Testimonials
  └── FAQs

Contact
  ├── Contact Departments
  └── Contact Messages (inbox)

Resources
  ├── Publications
  ├── Forms & Templates
  └── News Posts

CMS Pages
  └── Pages (privacy/terms/cookies)
```

### 10.3 Example CRUD Controller: ServiceController (Admin)

```php
// app/Http/Controllers/Admin/ServiceController.php

public function index()
{
    $services = Service::with('category')->orderBy('sort_order')->paginate(20);
    $categories = ServiceCategory::orderBy('sort_order')->get();
    return view('admin.services.index', compact('services', 'categories'));
}

public function create()
{
    $categories = ServiceCategory::orderBy('sort_order')->get();
    return view('admin.services.create', compact('categories'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'service_category_id' => 'required|exists:service_categories,id',
        'title'               => 'required|string|max:255',
        'price'               => 'required|string|max:50',
        'description'         => 'required|string',
        'badge'               => 'nullable|in:NEW,SPECIAL',
        'sort_order'          => 'integer',
        'is_active'           => 'boolean',
    ]);

    Service::create($data);
    return redirect()->route('admin.services.index')->with('success', 'Service created.');
}

public function edit(Service $service)
{
    $categories = ServiceCategory::orderBy('sort_order')->get();
    return view('admin.services.edit', compact('service', 'categories'));
}

public function update(Request $request, Service $service)
{
    $data = $request->validate([...same rules as store...]);
    $service->update($data);
    return redirect()->route('admin.services.index')->with('success', 'Service updated.');
}

public function destroy(Service $service)
{
    $service->delete();
    return back()->with('success', 'Service deleted.');
}
```

### 10.4 Example CRUD Controller: HeroSlideController (Admin)

```php
public function store(Request $request)
{
    $request->validate([
        'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        'alt_text'   => 'nullable|string|max:255',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ]);

    // Store the file in the 'hero' subdirectory of the public disk
    $path = $request->file('image')->store('hero', 'public');

    HeroSlide::create([
        'image_path' => $path,
        'alt_text'   => $request->alt_text,
        'sort_order' => $request->sort_order ?? 0,
        'is_active'  => $request->boolean('is_active', true),
    ]);

    return redirect()->route('admin.hero-slides.index')->with('success', 'Slide added.');
}

public function update(Request $request, HeroSlide $heroSlide)
{
    $request->validate([
        'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        'alt_text'   => 'nullable|string|max:255',
        'sort_order' => 'integer',
        'is_active'  => 'boolean',
    ]);

    if ($request->hasFile('image')) {
        // Delete the old file
        Storage::disk('public')->delete($heroSlide->image_path);
        $heroSlide->image_path = $request->file('image')->store('hero', 'public');
    }

    $heroSlide->fill([
        'alt_text'   => $request->alt_text,
        'sort_order' => $request->sort_order ?? $heroSlide->sort_order,
        'is_active'  => $request->boolean('is_active'),
    ])->save();

    return redirect()->route('admin.hero-slides.index')->with('success', 'Slide updated.');
}
```

The same file upload pattern (validate image, `store('subdirectory', 'public')`, delete old file on update) applies to: SiteSettings (logo, office_image), CaseStudy (image), TeamMember (image), Achievement (image), NewsPost (image), AboutSettings (hero_image), and Testimonial (avatar).

### 10.5 Example Blade View: Services Index

```blade
{{-- resources/views/admin/services/index.blade.php --}}
@extends('layouts.admin')
@section('page-title', 'Services')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">All Services</h2>
    <a href="{{ route('admin.services.create') }}"
       class="bg-primary text-white px-4 py-2 rounded hover:bg-green-600 text-sm font-medium">
        + Add Service
    </a>
</div>

{{-- Filter by category --}}
<div class="mb-4 flex gap-2 flex-wrap" x-data="{ active: 'all' }">
    <button @click="active = 'all'"
            :class="active === 'all' ? 'bg-primary text-white' : 'bg-white text-gray-600 border'"
            class="px-3 py-1 rounded text-sm transition">All</button>
    @foreach($categories as $cat)
    <button @click="active = '{{ $cat->slug }}'"
            :class="active === '{{ $cat->slug }}' ? 'bg-primary text-white' : 'bg-white text-gray-600 border'"
            class="px-3 py-1 rounded text-sm transition">{{ $cat->label }}</button>
    @endforeach
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">Title</th>
                <th class="px-4 py-3 text-left">Category</th>
                <th class="px-4 py-3 text-left">Price</th>
                <th class="px-4 py-3 text-left">Badge</th>
                <th class="px-4 py-3 text-left">Order</th>
                <th class="px-4 py-3 text-left">Active</th>
                <th class="px-4 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($services as $service)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $service->title }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $service->category->label }}</td>
                <td class="px-4 py-3 text-primary font-semibold">{{ $service->price }}</td>
                <td class="px-4 py-3">
                    @if($service->badge)
                        <span class="px-2 py-0.5 text-xs rounded
                            {{ $service->badge === 'NEW' ? 'bg-primary text-white' : 'bg-orange-500 text-white' }}">
                            {{ $service->badge }}
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $service->sort_order }}</td>
                <td class="px-4 py-3">
                    <span class="w-2 h-2 rounded-full inline-block
                        {{ $service->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.services.edit', $service) }}"
                       class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                          x-data
                          @submit.prevent="if(confirm('Delete this service?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline text-xs">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">{{ $services->links() }}</div>
</div>
@endsection
```

### 10.6 Blade Form Partials

Reusable Blade components live in `resources/views/components/`. They accept props and keep forms consistent.

The `<x-form-input>` component renders a labeled `<input>` with error styling. The `<x-form-select>` renders a labeled `<select>`. The `<x-form-textarea>` renders a labeled `<textarea>`. The `<x-image-preview>` component takes a current image URL and shows a preview; when a new file is chosen it uses Alpine.js to show a local preview with `URL.createObjectURL`.

```blade
{{-- resources/views/components/image-preview.blade.php --}}
@props(['current' => null, 'name' => 'image', 'label' => 'Image'])

<div x-data="{ preview: '{{ $current }}' }">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @if($current)
        <img :src="preview" class="h-24 w-auto rounded mb-2 object-cover border">
    @endif
    <input
        type="file"
        name="{{ $name }}"
        accept="image/*"
        @change="preview = URL.createObjectURL($event.target.files[0])"
        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
               file:rounded file:border-0 file:text-sm file:bg-primary file:text-white
               hover:file:bg-green-600"
    >
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
```

### 10.7 Dashboard

The dashboard shows at-a-glance counts for every major entity and the last five unread contact messages.

```php
// app/Http/Controllers/Admin/DashboardController.php
public function index()
{
    $stats = [
        'hero_slides'    => HeroSlide::count(),
        'services'       => Service::count(),
        'case_studies'   => CaseStudy::count(),
        'team_members'   => TeamMember::count(),
        'publications'   => Publication::count(),
        'news_posts'     => NewsPost::count(),
        'unread_messages'=> ContactMessage::where('is_read', false)->count(),
        'total_messages' => ContactMessage::count(),
    ];
    $recentMessages = ContactMessage::latest()->take(5)->get();
    return view('admin.dashboard', compact('stats', 'recentMessages'));
}
```

---

## 11. CORS Configuration

```php
// config/cors.php
return [
    'paths'               => ['api/*'],
    'allowed_methods'     => ['*'],
    'allowed_origins'     => array_values(array_filter(array_map(
        'trim',
        explode(',', env('FRONTEND_URLS', env('FRONTEND_URL', 'http://localhost:3000')))
    ))),
    'allowed_origins_patterns' => [],
    'allowed_headers'     => ['*'],
    'exposed_headers'     => [],
    'max_age'             => 0,
    'supports_credentials'=> false,
];
```

---

## 12. Media File Migration Plan

When first deploying, the following files must be copied from the Next.js `frontend/public/` directory into `backend/storage/app/public/` to preserve existing assets:

| Source in frontend | Destination in backend storage |
| --- | --- |
| `public/logo/gc.png` | `logo/gc.png` |
| `public/Background Img/hero1.jpg` | `hero/hero1.jpg` |
| `public/Background Img/hero2..jpg` | `hero/hero2..jpg` |
| `public/Background Img/hero3.jpg` | `hero/hero3.jpg` |
| `public/about/hero.png` | `about/hero.png` |
| `public/about/avatar1.png` | `about/avatar1.png` |
| `public/about/avatar2.png` | `about/avatar2.png` |
| `public/about/avatar3.png` | `about/avatar3.png` |

After copying, run `php artisan storage:link` once to create the `public/storage` symlink.

---

## 13. API Controllers (Thin Read Layer)

API controllers query models and return JSON. They do not contain business logic. Each follows this structure:

```php
// app/Http/Controllers/Api/V1/ServiceController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    // GET /api/v1/services/categories
    public function categories()
    {
        return response()->json([
            'data' => ServiceCategory::orderBy('sort_order')->get(['id','label','slug','sort_order']),
        ]);
    }

    // GET /api/v1/services — returns all categories with their active services
    public function index(Request $request)
    {
        $query = ServiceCategory::with(['services' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])->orderBy('sort_order');

        if ($request->filled('category')) {
            return response()->json([
                'data' => $query->where('slug', $request->query('category'))->firstOrFail(),
            ]);
        }

        return response()->json([
            'data' => $query->get(),
        ]);
    }

}
```

The complete API response shapes are documented in the separate `API.md` file.

---

## 14. Artisan Commands Quick Reference

```bash
# Install the Laravel 12 backend with MySQL selected
composer global require laravel/installer
laravel new backend --database=mysql --phpunit --no-boost

# Install PHP dependencies
composer install

# Install Tailwind CSS v4 / Vite frontend assets for Blade admin
npm install
npm run build

# Install Sanctum
composer require laravel/sanctum

# Run migrations and seed
php artisan migrate --seed

# Create storage symlink
php artisan storage:link

# Clear all caches after config changes
php artisan optimize:clear

# Create a new admin (interactive)
php artisan tinker
>>> Admin::create(['name'=>'...','email'=>'...','password'=>'...'])
```

---

## 15. Notes On Known Frontend Content Issues

The following issues were flagged in the frontend inventory. They should be corrected in the seeded data or the admin panel rather than propagated:

The `Contact US` nav label uses unusual capitalisation; the seeder reproduces it as-is to not break any existing hash-match logic, but the admin can fix it. The `Any Quires` department title is seeded as `Any Queries`. The `Govt. Gaget` publication category is seeded as `Govt. Gazette`. Mojibake characters in the copyright text, FAQ minus sign, and mission bullet dash are all corrected in the seeders by using clean UTF-8 em-dashes and standard bullet points. Service prices are seeded as USD strings (`$75`) to match the existing frontend; the admin should update them to BDT when real pricing is confirmed. The about hero content (`Workshops that awesome!`) is template placeholder text; the admin should update it.
