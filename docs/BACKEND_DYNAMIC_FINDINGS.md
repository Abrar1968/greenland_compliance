# Backend/API Coverage Findings for Dynamic Frontend

Date: 2026-05-17
Scope: Frontend pages under frontend/src/app and shared components (layout, navbar, footer, hero). Verified backend API routes and controllers under backend/routes/api.php and backend/app/Http/Controllers/Api/V1 plus related models and accessors.

## Result
No missing backend/API endpoints or fields were found for the current frontend pages. All dynamic data used by the frontend is covered by the implemented API and matches the documented shapes in docs/API.md and docs/FRONTEND_DYNAMIC.md.

Frontend rendering has been updated to remove static content fallbacks for API-driven data. Components now render only API-provided content or leave sections empty when data is unavailable.

## Coverage Map (Frontend -> API)
- Layout (site metadata, navbar/footer): GET /api/v1/site, GET /api/v1/navigation.
- Home hero: GET /api/v1/hero.
- Services: GET /api/v1/services.
- Case studies + sidebar testimonials + presentation: GET /api/v1/case-studies, GET /api/v1/testimonials?page=case_studies, GET /api/v1/site.
- About page (all tabs, testimonials, presentation): GET /api/v1/about.
- Contact page (info, map, social, departments) + form submit: GET /api/v1/contact, POST /api/v1/contact.
- Resources (publications, forms, news): GET /api/v1/resources/publications, GET /api/v1/resources/forms, GET /api/v1/resources/news.
- CMS pages: GET /api/v1/pages/{slug} for privacy/terms/cookies.

## Notes
- The frontend expects absolute media URLs from the API. Backend models use accessors (HasPublicUrl) to provide these.
- Static fallback content has been removed from API-backed frontend sections. Remaining hardcoded frontend text is limited to UI labels, form placeholders, tab controls, empty-state copy, and intentionally visual controls.
- Publications, forms, and news links now render only when an API URL exists; missing URLs render a disabled/unavailable visual state instead of a permanent fake `href="#"`.

## Verification
- `php artisan migrate:fresh --seed` passed against MySQL database `greenland_compliance`.
- `php artisan --version` confirmed Laravel Framework 12.58.0.
- `php -m` confirmed the PHP GD extension is enabled.
- `php artisan route:list --path=api/v1` confirmed all documented public API routes are registered.
- `php artisan route:list --path=admin` confirmed admin routes are registered.
- API endpoint smoke tests confirmed every checked public `GET` endpoint returns a successful `{ data: ... }` envelope.
- `POST /api/v1/contact` with an empty payload returned the documented validation shape: `{ "error": "Validation failed", "messages": ... }`.
- `GET /api/v1/pages/not-found-test` returned the documented 404 shape: `{ "error": "Resource not found." }`.
- Seeder coverage check confirmed core dynamic content exists for site settings, navigation, hero slides, services, case studies, about settings, CMS pages, and admin user.
- `php artisan test` passed.
- Added executable admin coverage in `backend/tests/Feature/AdminCrudTest.php`.
- `php artisan test --filter=AdminCrudTest` passed: 4 tests, 349 assertions. Coverage includes every configured admin CRUD resource, settings forms, contact-message inbox actions, and reorder endpoint.
- Full backend `php artisan test` passed: 6 tests, 351 assertions.
- Frontend `npm run lint` passed.
- Frontend `npm run build` passed.
- Local production-server HTTP smoke checks confirmed every frontend route responds while the Laravel API is running: `/`, `/about`, `/services`, `/case-studies`, `/contact`, `/resources`, `/privacy`, `/terms`, and `/cookies`.
- Seeded API content checks confirmed the frontend data endpoints return populated `{ data: ... }` responses for site, navigation, hero, services, case studies, about, contact, resources, and CMS pages.
- Installed Playwright browser testing in the frontend and added `frontend/tests/e2e/frontend-dynamic-render.spec.ts`.
- Added `npm run test:e2e` for repeatable browser verification.
- Browser-level Playwright verification passed in installed Chrome: 9 tests passed. It verifies all public pages against live API data, contact validation through the backend, resource tabs, CMS pages, CMS content, and failed optimized image responses.
- Fixed local browser rendering issues discovered by Playwright:
  - API-backed frontend routes are now dynamic server-rendered on demand instead of being prerendered with empty data when the API is unavailable at build time.
  - Local backend storage images support `127.0.0.1:8000` in Next image config.
  - Frontend API helpers now use `cache: "no-store"` so admin/backend content changes are not hidden by stale Next fetch cache.
- Updated frontend Next.js from 16.2.4 to 16.2.6 after `npm audit --omit=dev` reported high-severity advisories against the pinned version. A moderate advisory remains in Next's nested PostCSS dependency; npm only offers `npm audit fix --force`, which attempts a breaking major downgrade path and was not applied.
