# Greenland Business & Compliance Frontend

This directory contains the Next.js frontend for Greenland Business & Compliance.

The current frontend was originally static. The implementation plan is to keep the existing visual structure unchanged and replace hard-coded page content with data from the Laravel REST API documented in the repository docs.

## Required Reading Before Implementation

Before editing frontend code, read the relevant local Next.js documentation under:

```text
node_modules/next/dist/docs/
```

The installed Next.js version may have breaking changes compared with older examples. Follow the local docs for App Router, data fetching, caching, images, and config behavior.

## Main Docs

- `../docs/FRONTEND_DYNAMIC.md` - complete static-to-dynamic migration plan.
- `../docs/API.md` - public Laravel REST API contract consumed by the frontend.
- `../docs/BACKEND.md` - Laravel backend and Blade admin implementation plan.
- `./FRONTEND_STATIC_INVENTORY.md` - factual snapshot of the current static frontend.

## Environment

Create `frontend/.env.local` locally:

```dotenv
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
NEXT_PUBLIC_SITE_URL=http://localhost:3000
```

Production values:

```dotenv
NEXT_PUBLIC_API_URL=<BACKEND_URL>/api/v1
NEXT_PUBLIC_SITE_URL=https://greenlandcompliance.com
```

## Development

Install dependencies and run the frontend from this directory:

```bash
npm install
npm run dev
```

Then open:

```text
http://localhost:3000
```

## Migration Rules

- Do not change the visual layout while converting static data to API data.
- Do not edit CSS modules unless the static source already requires it.
- Keep API responses wrapped in the documented `data` envelope.
- Use `NEXT_PUBLIC_API_URL` for backend API calls.
- Use `NEXT_PUBLIC_SITE_URL` as the canonical frontend URL.
- Remove unused Prisma files and package entries after the frontend fully uses the Laravel API.
