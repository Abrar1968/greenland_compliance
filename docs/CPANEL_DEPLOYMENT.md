# cPanel Deployment Guide

Testing domains:

- Frontend: `https://auxtechsoftware.com`
- Backend API and admin: `https://api.auxtechsoftware.com`

Production domains later:

- Frontend: `https://greenlandcompliance.com`
- Backend API/admin: set the final backend subdomain in `APP_URL`, `ADMIN_PANEL_URL`, and frontend `NEXT_PUBLIC_API_URL`.

## Backend: Laravel 12

Upload the `backend/` project to the hosting account.

The subdomain `api.auxtechsoftware.com` must point to:

```text
backend/public
```

Do not point the subdomain to the backend project root. Laravel's public document root must be `public/`.

Use `backend/.env.production.example` as the production `.env` template.

Required production values:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.auxtechsoftware.com
ADMIN_PANEL_URL=https://api.auxtechsoftware.com/admin
FRONTEND_URL=https://auxtechsoftware.com
FRONTEND_URLS=https://auxtechsoftware.com,https://www.auxtechsoftware.com,https://greenlandcompliance.com,https://www.greenlandcompliance.com
FILESYSTEM_DISK=public
SESSION_DRIVER=database
SESSION_ENCRYPT=true
SESSION_DOMAIN=.auxtechsoftware.com
```

Set the real cPanel MySQL database values:

```dotenv
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpanel_database_name
DB_USERNAME=cpanel_database_user
DB_PASSWORD=cpanel_database_password
```

After upload, run from the backend project root:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If you already seeded locally and import the database manually, do not run `db:seed --force` again unless you want to reset/create seed records.

The admin panel will be:

```text
https://api.auxtechsoftware.com/admin
```

The API base URL will be:

```text
https://api.auxtechsoftware.com/api/v1
```

## Backend `.htaccess`

The Laravel `.htaccess` is in:

```text
backend/public/.htaccess
```

It sends all non-file requests to `index.php` and redirects real production hosts to HTTPS. It is correct only when the domain document root is `backend/public`.

## Frontend: Next.js

This frontend is a dynamic Next.js app. It is not a plain static HTML upload.

Use cPanel's Node.js application feature if available:

1. Upload `frontend/` to the hosting account.
2. Set the Node app root to `frontend/`.
3. Use Node.js 20 or newer.
4. Add production env values from `frontend/.env.production.example`.
5. Install dependencies:

```bash
npm install
```

6. Build:

```bash
npm run build
```

7. Start:

```bash
npm run start
```

If your cPanel does not support Node.js apps, this Next.js frontend cannot be hosted correctly as a normal static `public_html` upload. Use a Node-capable host, VPS, or a platform such as Vercel/Netlify for the frontend.

## Verification URLs

After deployment, verify:

```text
https://api.auxtechsoftware.com/api/v1/site
https://api.auxtechsoftware.com/api/v1/navigation
https://api.auxtechsoftware.com/api/v1/hero
https://api.auxtechsoftware.com/admin/login
https://auxtechsoftware.com
https://auxtechsoftware.com/services
https://auxtechsoftware.com/case-studies
https://auxtechsoftware.com/about
https://auxtechsoftware.com/contact
https://auxtechsoftware.com/resources
```

Every API success response must contain:

```json
{ "data": {} }
```

## Common cPanel Mistakes

- Do not upload Laravel so that `index.php` is outside the domain document root.
- Do not expose the Laravel project root publicly.
- Do not leave `APP_DEBUG=true` on hosting.
- Do not keep the default seeded admin password in production.
- Do not set frontend API URL to localhost on hosting.
- Do not skip `php artisan storage:link`; uploads will not load without the public storage symlink.
- Do not cache config before updating `.env`; if you change `.env`, rerun `php artisan config:cache`.
