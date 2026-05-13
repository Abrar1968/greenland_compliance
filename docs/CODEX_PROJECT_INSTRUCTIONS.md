# Greenland Compliance Codex Project Instructions

Use this file as the project instruction source for Codex work on the Greenland Compliance repository.

## Project Shape

This project is a separated frontend/backend system:

- Frontend: Next.js, inside `frontend/`
- Backend: Laravel 12, inside `backend/`
- Admin panel: Laravel Blade, inside `backend/`
- Database: MySQL
- Backend/admin styling: Tailwind CSS v4
- Frontend/backend communication: Laravel REST API only

Do not create duplicate backend folders, alternate implementation folders, temporary Laravel folders, or parallel architectures. Use only `backend/` for backend implementation.

## Absolute Source Of Truth

Before backend or API work, read these files fully in this exact order:

1. `docs/API.md`
2. `docs/BACKEND.md`
3. `docs/FRONTEND_DYNAMIC.md`
4. `frontend/FRONTEND_STATIC_INVENTORY.md`

Then implement according to the docs.

`docs/API.md` is the exact public API contract.
`docs/BACKEND.md` is the exact Laravel/database/admin implementation contract.
`docs/FRONTEND_DYNAMIC.md` explains how the frontend will consume the API.
`frontend/FRONTEND_STATIC_INVENTORY.md` is the factual snapshot of the current static frontend.

If any docs conflict, stop and report the exact conflict with file/line references before coding.

## Backend Stack Requirements

- Use Laravel 12 only.
- Use PHP 8.2 or higher.
- Use MySQL only. Do not use SQLite for implementation or verification unless the user explicitly asks.
- Use Laravel Blade for the admin panel.
- Use Tailwind CSS v4 for admin styling.
- Use Laravel Vite/Tailwind v4 setup as documented.
- Use Laravel public storage disk for uploaded files.
- Use PHP GD extension for backend image validation/processing/resizing/compression.
- Use session-based admin authentication.
- Public API endpoints must be stateless and unauthenticated except where docs say otherwise.

## Folder Rules

Use only these main folders:

- `backend/` for Laravel backend and admin panel
- `frontend/` for Next.js frontend
- `docs/` for documentation

Never create:

- `backend12/`
- `backend_laravel11_temp/`
- `backend_old/`
- `backend_backup/`
- Any duplicate backend folder
- Any replacement implementation outside `backend/`

If a temporary folder is absolutely necessary, ask first.

## Backend Implementation Rules

Implement `docs/BACKEND.md` strictly.

Do not skip:

- Migrations
- Models
- Fillable fields
- Casts
- Relationships
- Accessors for media/file URLs
- Seeders
- Admin authentication
- Admin routes
- Admin CRUD controllers
- Admin Blade views
- Reorder controller
- Contact message inbox
- Public API routes
- API controllers
- CORS config
- Storage setup
- Tailwind/Vite setup
- `.env.example` values

Every documented backend resource must exist:

- Admin
- SiteSetting
- SocialLink
- NavItem
- HeroSetting
- HeroSlide
- ServiceCategory
- Service
- CaseStudyCategory
- CaseStudy
- Testimonial
- TimelineMilestone
- AboutSetting
- MissionBullet
- ApproachCard
- Achievement
- Partner
- TeamMember
- Faq
- ContactDepartment
- Publication
- FormTemplate
- NewsPost
- ContactMessage
- Page

Every documented admin controller must exist.
Every documented API controller must exist.
Every documented route must exist.

## API Contract Rules

Implement `docs/API.md` exactly.

Every successful public API response must use:

```json
{ "data": ... }
```

Validation errors must use:

```json
{
  "error": "Validation failed",
  "messages": {}
}
```

404 errors must use:

```json
{ "error": "Resource not found." }
```

Do not return undocumented response shapes.
Do not rename fields.
Do not omit fields.
Do not change endpoint paths.
Do not add alternative frontend endpoints unless docs explicitly allow them.

Canonical API prefix:

```text
/api/v1
```

Required public endpoints include:

- `GET /api/v1/site`
- `GET /api/v1/navigation`
- `GET /api/v1/hero`
- `GET /api/v1/services`
- `GET /api/v1/services/categories`
- `GET /api/v1/case-studies`
- `GET /api/v1/case-studies/categories`
- `GET /api/v1/case-studies/{slug}`
- `GET /api/v1/about`
- `GET /api/v1/contact`
- `POST /api/v1/contact`
- `GET /api/v1/resources/publications`
- `GET /api/v1/resources/forms`
- `GET /api/v1/resources/news`
- `GET /api/v1/testimonials`
- `GET /api/v1/pages/{slug}`

Services routing rule:

- `GET /services` is canonical.
- Optional filter is `?category=slug`.
- Do not create or consume `/services/category/{slug}` unless docs are updated.

## Admin Panel Rules

The admin panel is the write surface for all dynamic content.

Admin must support:

- Login
- Logout
- Dashboard
- Site settings edit/update
- Hero settings edit/update
- Full CRUD for all documented content resources
- Upload replacement where media/file fields exist
- Delete old media/file on replacement where applicable
- Active/inactive status where documented
- Sort order where documented
- Reorder endpoint where documented
- Contact message inbox
- Mark contact message as read
- Delete contact messages

Admin routes must use the documented `/admin` prefix unless docs are changed.

Admin Blade views must use Tailwind CSS v4 and keep the UI practical, clear, and CRUD-complete.

## Database Rules

Use MySQL.

`.env.example` must include MySQL config:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=greenland_compliance
DB_USERNAME=root
DB_PASSWORD=
```

Migrations must match `docs/BACKEND.md`.
Foreign keys must be created in dependency order.
Seeders must populate enough data for every frontend page to render dynamically without missing sections.
Seed data must preserve current static frontend structure while correcting documented typos/encoding issues.
Do not seed SQLite-specific behavior.

## Media, Image, And File Rules

Uploaded files live on Laravel public disk.

Run/storage expectation:

```bash
php artisan storage:link
```

API must return absolute URLs using backend `APP_URL`, for example:

```text
<BACKEND_URL>/storage/hero/hero1.jpg
```

Frontend must not construct storage URLs manually.

For nullable media fields, API returns `null`.

Accessors must exist for documented media/file URL fields, including:

- `logo_url`
- `office_image_url`
- `company_presentation_url`
- `image_url`
- `avatar_url`
- `file_url`
- `logo_url`

### GD Extension Image Upload Rules

Use PHP's GD extension for backend image upload processing.

Before implementing image upload processing, verify GD is available:

```bash
php -m | grep gd
```

On Windows PowerShell:

```powershell
php -m | Select-String gd
```

If GD is missing, stop and report that PHP GD must be enabled in `php.ini` before image upload processing can be verified.

Image upload logic must:

- Validate uploaded files with Laravel validation rules.
- Accept only documented image types for each resource.
- Use GD-backed image inspection/processing functions such as `getimagesize`, `imagecreatefromjpeg`, `imagecreatefrompng`, `imagecreatefromwebp`, `imagejpeg`, `imagepng`, and `imagewebp` where resizing/compression is needed.
- Preserve transparency for PNG/WebP when processing images.
- Store processed files on the Laravel `public` disk.
- Delete old image files when replacing an existing image.
- Return only the documented path/accessor URL fields through the API.
- Never expose local filesystem paths in API responses.

Resources with image upload behavior include:

- Site logo
- Site office image
- Hero slides
- Case study images
- Testimonial avatars
- About hero image
- Achievement images
- Partner logos
- Team member images
- News post images

Resources with file upload behavior include:

- Company presentation PDF
- Publications
- Form templates

Large hero or media files must either be optimized during upload using GD or rejected with clear validation. If upload limits need changing, document and configure the PHP/Laravel limits explicitly.

## CORS And Environment Rules

Backend must support env-driven frontend URLs.

Required env variables:

```dotenv
APP_URL=http://localhost:8000
ADMIN_PANEL_URL=http://localhost:8000/admin
FRONTEND_URL=http://localhost:3000
FRONTEND_URLS=http://localhost:3000,https://greenlandcompliance.com,https://www.greenlandcompliance.com
```

Production frontend domain:

```text
https://greenlandcompliance.com
```

CORS must allow configured frontend origins.

## Frontend Awareness Rules

Do not implement frontend unless explicitly asked.

However, backend/API must support the frontend dynamic migration exactly.

Do not break current static visual structure assumptions.

The frontend expects the API fields and nested shapes documented in `API.md`.

Footer, navbar, hero, services, case studies, about, contact, resources, testimonials, and CMS pages must all have complete API-backed data.

## Laravel 12 Setup Rules

If backend is not set up, create it in `backend/` only.

Use:

```bash
composer global require laravel/installer
laravel new backend --database=mysql --phpunit --no-boost
```

If `backend/` already exists, inspect it first. Do not overwrite without approval.

After setup, confirm:

```bash
php artisan --version
composer show laravel/framework
```

Laravel must be version 12.x.

## Tailwind CSS v4 Rules

Backend/admin must use Tailwind CSS v4.

Laravel 12 frontend asset stack should include:

- `tailwindcss`
- `@tailwindcss/vite`
- `vite`
- `laravel-vite-plugin`

Admin layout should load assets through:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Do not use old Tailwind CDN config as the final implementation.

## Verification Requirements

After implementation, run and report results:

```bash
php artisan --version
composer show laravel/framework
php artisan migrate:fresh --seed
php artisan route:list
php artisan test
npm run build
```

Also verify GD:

```bash
php -m
```

Verification must confirm:

- Laravel is 12.x
- Database is MySQL
- PHP GD extension is enabled
- All migrations run
- Seeders run
- Admin user exists
- All API routes exist
- All admin routes exist
- Every API endpoint returns `{ data: ... }` on success
- Contact validation returns documented error shape
- Missing resource returns documented 404 shape
- Tailwind CSS v4 build succeeds

If MySQL is not available locally, stop and report that verification is blocked by database connectivity. Do not silently switch to SQLite.

If GD is not enabled locally, stop and report that image processing verification is blocked by the missing PHP GD extension. Do not silently skip image processing verification.

## Quality Bar

Do not say "done" until:

- All documented backend files are implemented
- All documented API endpoints are implemented
- All documented admin resources are implemented
- Seed data covers all frontend sections
- Image/file upload handling is implemented and GD-backed image processing is verified
- Verification has been run or a real blocker is reported
- No duplicate backend folders exist
- `git status` has been checked and summarized

If implementation is too large for one turn, continue working in phases but keep the same rules. Do not drop sections silently.
