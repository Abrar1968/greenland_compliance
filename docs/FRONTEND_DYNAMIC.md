# Greenland Business & Compliance — Frontend Dynamic Migration Specification

Project: Greenland Business & Compliance
Repository: https://github.com/Abrar1968/greenland_compliance.git
Frontend path inside repo: `frontend/`
Document purpose: Complete specification for converting the static Next.js frontend into a fully dynamic application that consumes the Laravel REST API. Every piece of content currently hard-coded in the source must become an API call. This document describes every file that changes, every TypeScript interface, every fetch pattern, every ISR strategy, and every edge-case. Nothing in the visual design, CSS, Tailwind classes, Lucide icon usage, layout structure, or CSS module rules changes.

---

## 1. Core Principle: Data Source Migration Only

The guiding rule throughout this entire migration is that only the *origin* of data changes — from inline TypeScript arrays to API responses. Every Tailwind class, every Lucide icon import, every CSS module rule, every `next/image` prop, every layout grid, and every animation stays identical to what is in the static source. A person looking at the rendered pages before and after this migration should see no visual difference whatsoever.

There are three categories of change:

The first category is data-source replacement, where a hard-coded array or string literal is deleted and replaced with a value fetched from an API endpoint. The JSX that consumes that data does not change.

The second category is wiring, where functions that were no-ops (form submit buttons with no handler, download buttons with `href="#"`) are connected to real API calls or real URLs from the API response.

The third category is new files, meaning the three missing policy pages (`/privacy`, `/terms`, `/cookies`) are created as dynamic CMS-page consumers and the central API client library is added.

Nothing else changes.

---

## 2. File Additions And Modifications Overview

The table below lists every file that is created or modified. Files not in this table are untouched.

| Action | Path | Reason |
| --- | --- | --- |
| Modify | `next.config.ts` | Add `remotePatterns` for the API storage domain |
| Add | `.env.local` (not committed) | `NEXT_PUBLIC_API_URL` and `NEXT_PUBLIC_SITE_URL` environment variables |
| Add | `src/lib/api.ts` | Central typed fetch helper for all API calls |
| Add | `src/types/api.ts` | TypeScript interfaces for every API response shape |
| Modify | `src/app/layout.tsx` | Fetch site settings and navigation; pass as props |
| Modify | `src/components/Navbar.tsx` | Accept props; remove static arrays and hard-coded strings |
| Modify | `src/components/Footer.tsx` | Accept props; remove static arrays and hard-coded strings |
| Modify | `src/components/Hero.tsx` | Accept props; remove static slide array and hard-coded text |
| Modify | `src/app/page.tsx` | Fetch hero data; pass to `<Hero>` |
| Modify | `src/app/services/page.tsx` | Fetch services from API; replace static tab and service arrays |
| Modify | `src/app/case-studies/page.tsx` | Fetch case studies and testimonials from API |
| Modify | `src/app/about/page.tsx` | Fetch all about data in one call; replace all static arrays |
| Modify | `src/app/contact/page.tsx` | Add `'use client'`; fetch contact info; wire form submission |
| Modify | `src/app/resources/page.tsx` | Fetch publications, forms, and news from API |
| Add | `src/app/privacy/page.tsx` | CMS page consumer for `/privacy` slug |
| Add | `src/app/terms/page.tsx` | CMS page consumer for `/terms` slug |
| Add | `src/app/cookies/page.tsx` | CMS page consumer for `/cookies` slug |
| Delete | `src/lib/prisma.ts` | No longer used; Prisma is replaced by the Laravel API |
| Delete | `prisma.config.ts` | No longer used |
| Delete | `prisma/schema.prisma` | No longer used |
| Delete | `prisma/seed.ts` | No longer used |
| Modify | `package.json` / `package-lock.json` | Remove unused Prisma package entries and regenerate lockfile |

---

## 2.1 Implementation Prerequisite: Read The Installed Next.js Docs

Before editing any Next.js source file, read the relevant guide under `frontend/node_modules/next/dist/docs/`. The repository-level `AGENTS.md` says this project uses a Next.js version with breaking changes, so implementation must follow the installed local documentation instead of older framework assumptions.

At minimum, check the local docs for:

- App Router Server Components and Client Components.
- `next/image` remote image configuration.
- Route handlers, metadata, caching, and revalidation behavior used by the current installed Next.js version.

Do this before changing `layout.tsx`, page files, `next.config.ts`, or data-fetching code.

---

## 3. Environment Configuration

Create a `.env.local` file in the `frontend/` root. This file must not be committed to the repository (confirm `.gitignore` already excludes `.env.local` — Next.js default `.gitignore` does).

```dotenv
# .env.local
# Base URL of the Laravel backend API, no trailing slash.
# For local development this points to the Laravel dev server.
NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
NEXT_PUBLIC_SITE_URL=http://localhost:3000

# For production, set these to the real deployed URLs:
# NEXT_PUBLIC_API_URL=<BACKEND_URL>/api/v1
# NEXT_PUBLIC_SITE_URL=https://greenlandcompliance.com
```

The variables are prefixed with `NEXT_PUBLIC_` so they are available in both Server Components and Client Components. `NEXT_PUBLIC_SITE_URL` is the canonical frontend URL; for production it must be `https://greenlandcompliance.com`. `NEXT_PUBLIC_API_URL` is the Laravel REST API base URL and must be provided from the backend deployment URL.

---

## 4. `next.config.ts` — Remote Image Patterns

The existing `next.config.ts` only sets `turbopack.root`. It must also declare the Laravel storage domain as a permitted remote image host, because `next/image` refuses to optimise images from unknown origins.

```ts
// frontend/next.config.ts
import type { NextConfig } from 'next';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const projectRoot = path.dirname(fileURLToPath(import.meta.url));
const apiUrl = process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000/api/v1';
const apiOrigin = new URL(apiUrl);

const nextConfig: NextConfig = {
  turbopack: {
    root: projectRoot,
  },
  images: {
    remotePatterns: [
      {
        // Local Laravel dev server
        protocol: 'http',
        hostname: 'localhost',
        port: '8000',
        pathname: '/storage/**',
      },
      {
        // Backend storage host from NEXT_PUBLIC_API_URL.
        protocol: apiOrigin.protocol.replace(':', '') as 'http' | 'https',
        hostname: apiOrigin.hostname,
        port: apiOrigin.port,
        pathname: '/storage/**',
      },
    ],
  },
};

export default nextConfig;
```

The current project uses an ESM `next.config.ts`, so keep the `fileURLToPath(import.meta.url)` root calculation exactly as shown above. Do not replace it with `__dirname`. Every image that previously came from the `public/` directory now comes from the API as an absolute URL under `/storage/...`. The `remotePatterns` entries above cover both environments. The existing local `public/` images (logo, hero, about) are migrated to the Laravel storage by the backend team following the steps in `BACKEND.md` section 12, so those local paths remain only as transition fallbacks.

---

## 5. TypeScript Interfaces — `src/types/api.ts`

All API response shapes defined in `API.md` are represented here as TypeScript interfaces. Centralising them in one file makes them easy to import in any component or page without duplication.

```ts
// src/types/api.ts

// ─────────────────────────────────────────────────────────────────────────────
// Standard API envelope wrappers
// ─────────────────────────────────────────────────────────────────────────────

export interface ApiSingle<T> {
  data: T;
}

export interface ApiCollection<T> {
  data: T[];
}

export interface ApiGrouped<T> {
  data: Record<string, T[]>;
}

// ─────────────────────────────────────────────────────────────────────────────
// Site settings (GET /site)
// ─────────────────────────────────────────────────────────────────────────────

export interface SocialLink {
  id: number;
  platform: 'linkedin' | 'facebook' | 'twitter' | 'instagram' | 'youtube' | 'whatsapp' | string;
  url: string;
}

export interface SiteSettings {
  site_name: string;
  meta_title: string;
  meta_description: string;
  logo_url: string | null;
  primary_phone: string;
  primary_email: string;
  address: string;
  business_hours: string;
  footer_description: string;
  footer_cta_title: string;
  footer_cta_text: string;
  footer_cta_button_label: string;
  footer_cta_button_href: string;
  copyright_text: string;
  company_presentation_url: string | null;
  how_we_work_video_url: string | null;
  map_embed_url: string | null;
  office_image_url: string | null;
  social_links: SocialLink[];
}

// ─────────────────────────────────────────────────────────────────────────────
// Navigation (GET /navigation)
// ─────────────────────────────────────────────────────────────────────────────

export interface NavItem {
  id: number;
  label: string;
  href: string;
}

export interface Navigation {
  header: NavItem[];
  footer_quick: NavItem[];
  footer_services: NavItem[];
  footer_policy: NavItem[];
}

// ─────────────────────────────────────────────────────────────────────────────
// Hero (GET /hero)
// ─────────────────────────────────────────────────────────────────────────────

export interface HeroSlide {
  id: number;
  image_url: string;
  alt_text: string;
  sort_order: number;
}

export interface HeroData {
  headline_line1: string;
  headline_line2: string;
  paragraph: string;
  cta1_label: string;
  cta1_href: string;
  cta2_label: string;
  cta2_href: string;
  slides: HeroSlide[];
}

// ─────────────────────────────────────────────────────────────────────────────
// Services (GET /services)
// ─────────────────────────────────────────────────────────────────────────────

export interface ServiceItem {
  id: number;
  title: string;
  price: string;
  description: string;
  badge: 'NEW' | 'SPECIAL' | null;
  sort_order: number;
}

export interface ServiceCategory {
  id: number;
  label: string;
  slug: string;
  sort_order: number;
  services: ServiceItem[];
}

// ─────────────────────────────────────────────────────────────────────────────
// Case Studies (GET /case-studies)
// ─────────────────────────────────────────────────────────────────────────────

export interface CaseStudyCategory {
  id: number;
  name: string;
  slug: string;
  sort_order: number;
}

export interface CaseStudy {
  id: number;
  title: string;
  slug: string;
  image_url: string | null;
  summary: string | null;
  sort_order: number;
  category: CaseStudyCategory;
}

// ─────────────────────────────────────────────────────────────────────────────
// Testimonials (GET /testimonials)
// ─────────────────────────────────────────────────────────────────────────────

export interface Testimonial {
  id: number;
  author: string;
  role: string;
  quote: string;
  avatar_url: string | null;
  sort_order: number;
}

// ─────────────────────────────────────────────────────────────────────────────
// About page (GET /about)
// ─────────────────────────────────────────────────────────────────────────────

export interface MissionBullet {
  id: number;
  text: string;
  sort_order: number;
}

export interface TimelineMilestone {
  id: number;
  year: string;
  title: string;
  description: string;
  sort_order: number;
}

export interface ApproachCard {
  id: number;
  title: string;
  icon: string;   // Lucide icon name string, e.g. "Plane", "TrendingUp"
  description: string;
  sort_order: number;
}

export interface Achievement {
  id: number;
  title: string;
  image_url: string | null;
  sort_order: number;
}

export interface Partner {
  id: number;
  name: string;
  industry: string | null;
  location: string | null;
  description: string | null;
  logo_url: string | null;
  sort_order: number;
}

export interface TeamMember {
  id: number;
  name: string;
  role: string;
  description: string | null;
  image_url: string | null;
  profile_slug: string | null;
  sort_order: number;
}

export interface FaqItem {
  id: number;
  question: string;
  answer: string;
  sort_order: number;
}

export interface AboutHero {
  heading_line1: string;
  heading_line2: string;
  paragraph: string;
  image_url: string | null;
  cta_label: string;
  cta_href: string;
}

export interface AboutOverview {
  paragraph1: string;
  paragraph2: string;
  callout: string;
  mission_heading: string;
  mission_intro: string;
  mission_bullets: MissionBullet[];
  how_we_work_video_url: string;
  timeline: TimelineMilestone[];
}

export interface AboutApproach {
  intro1: string;
  intro2: string;
  cards: ApproachCard[];
}

export interface AboutFooterCta {
  text: string;
  button_label: string;
  button_href: string;
}

export interface AboutData {
  banner_label: string;
  company_presentation_url: string | null;
  hero: AboutHero;
  overview: AboutOverview;
  approach: AboutApproach;
  achievements: Achievement[];
  partners: Partner[];
  team: TeamMember[];
  faqs: FaqItem[];
  testimonials: Testimonial[];
  footer_cta: AboutFooterCta;
}

// ─────────────────────────────────────────────────────────────────────────────
// Contact page (GET /contact)
// ─────────────────────────────────────────────────────────────────────────────

export interface ContactDepartment {
  id: number;
  title: string;
  email: string;
  sort_order: number;
}

export interface ContactInfo {
  banner_label: string;
  address: string;
  phone: string;
  email: string;
  map_embed_url: string | null;
  office_image_url: string | null;
  social_links: Omit<SocialLink, 'id'>[];
  departments: ContactDepartment[];
}

// ─────────────────────────────────────────────────────────────────────────────
// Resources (GET /resources/publications, /resources/forms, /resources/news)
// ─────────────────────────────────────────────────────────────────────────────

export type ResourceFormat = 'pdf' | 'word' | 'excel' | 'jpg' | 'png' | string;

export interface Publication {
  id: number;
  title: string;
  format: ResourceFormat;
  category: string;
  file_url: string | null;
  sort_order: number;
}

export interface FormTemplate {
  id: number;
  title: string;
  format: ResourceFormat;
  language: string;
  file_url: string | null;
  sort_order: number;
}

export interface NewsPost {
  id: number;
  title: string;
  category: string;
  published_at: string;    // ISO date string "2026-05-10"
  image_url: string | null;
  format: ResourceFormat;
  external_url: string | null;
  sort_order: number;
}

// ─────────────────────────────────────────────────────────────────────────────
// CMS Pages (GET /pages/{slug})
// ─────────────────────────────────────────────────────────────────────────────

export interface CmsPage {
  title: string;
  slug: string;
  content: string;   // raw HTML — render with dangerouslySetInnerHTML
}
```

---

## 6. Central API Client — `src/lib/api.ts`

This file is the single place where all fetch calls live. It reads `NEXT_PUBLIC_API_URL` from the environment, wraps every call with error handling, and exports one typed async function per endpoint. Components and pages import only the functions they need.

The ISR `revalidate` durations come directly from `API.md` section 6:

- `/hero` revalidates every 60 seconds.
- `/site` and `/navigation` revalidate every 300 seconds.
- `/about`, `/services`, `/case-studies`, `/resources/*`, `/testimonials`, `/pages/*` revalidate every 300 seconds.
- `/contact` (GET) must never be cached because it contains real-time contact data.
- `POST /contact` is a write operation and is never cached.

```ts
// src/lib/api.ts
import type {
  SiteSettings,
  Navigation,
  HeroData,
  ServiceCategory,
  CaseStudy,
  Testimonial,
  AboutData,
  ContactInfo,
  Publication,
  FormTemplate,
  NewsPost,
  CmsPage,
} from '@/types/api';

// The base URL comes from the environment variable defined in .env.local.
// NEXT_PUBLIC_ prefix makes it available in both Server Components and Client Components.
const BASE_URL = process.env.NEXT_PUBLIC_API_URL!;

// ─────────────────────────────────────────────────────────────────────────────
// Internal helper: fetch with typed response and Next.js ISR support
// ─────────────────────────────────────────────────────────────────────────────

// revalidate = number of seconds, 0 = no-store, undefined = default
async function apiFetch<T>(
  path: string,
  options?: RequestInit & { next?: { revalidate?: number } }
): Promise<T> {
  const url = `${BASE_URL}${path}`;
  const res = await fetch(url, {
    headers: { Accept: 'application/json' },
    ...options,
  });

  if (!res.ok) {
    // Throw a structured error so callers can distinguish 404 from 500.
    throw new Error(`API error ${res.status} on ${path}`);
  }

  // Every API response is wrapped in { data: ... }.
  const json = await res.json();
  return json.data as T;
}

// ─────────────────────────────────────────────────────────────────────────────
// Exported fetch functions — one per API endpoint
// ─────────────────────────────────────────────────────────────────────────────

/** GET /site — global site settings, logo, contact info, social links */
export async function fetchSiteSettings(): Promise<SiteSettings> {
  return apiFetch<SiteSettings>('/site', { next: { revalidate: 300 } });
}

/** GET /navigation — header, footer_quick, footer_services, footer_policy arrays */
export async function fetchNavigation(): Promise<Navigation> {
  return apiFetch<Navigation>('/navigation', { next: { revalidate: 300 } });
}

/** GET /hero — headline copy, CTAs, and ordered slide array */
export async function fetchHero(): Promise<HeroData> {
  return apiFetch<HeroData>('/hero', { next: { revalidate: 60 } });
}

/** GET /services — all categories with nested active services */
export async function fetchServices(): Promise<ServiceCategory[]> {
  // The API wraps the collection directly as an array under "data".
  // apiFetch returns json.data, which is the array itself.
  return apiFetch<ServiceCategory[]>('/services', { next: { revalidate: 300 } });
}

/** GET /case-studies — all active case studies with category */
export async function fetchCaseStudies(): Promise<CaseStudy[]> {
  return apiFetch<CaseStudy[]>('/case-studies', { next: { revalidate: 300 } });
}

/**
 * GET /testimonials?page=case_studies
 * Used by the case-studies sidebar. The `page` param filters by context.
 */
export async function fetchTestimonials(page?: string): Promise<Testimonial[]> {
  const qs = page ? `?page=${page}` : '';
  return apiFetch<Testimonial[]>(`/testimonials${qs}`, { next: { revalidate: 300 } });
}

/** GET /about — full about page payload (all six tab contents in one call) */
export async function fetchAbout(): Promise<AboutData> {
  return apiFetch<AboutData>('/about', { next: { revalidate: 300 } });
}

/** GET /contact — contact details, map URL, social links, department emails */
export async function fetchContactInfo(): Promise<ContactInfo> {
  // Contact info must NOT be cached; it may change at any moment.
  return apiFetch<ContactInfo>('/contact', { cache: 'no-store' });
}

/** GET /resources/publications */
export async function fetchPublications(): Promise<Publication[]> {
  return apiFetch<Publication[]>('/resources/publications', { next: { revalidate: 300 } });
}

/**
 * GET /resources/forms — returns an object keyed by category group name.
 * e.g. { "Human Resources": [...], "Legal & Compliance": [...] }
 */
export async function fetchFormTemplates(): Promise<Record<string, FormTemplate[]>> {
  // The /resources/forms response has data as an object, not an array.
  // apiFetch returns json.data, which is the grouped object directly.
  const url = `${BASE_URL}/resources/forms`;
  const res = await fetch(url, {
    headers: { Accept: 'application/json' },
    next: { revalidate: 300 },
  });
  if (!res.ok) throw new Error(`API error ${res.status} on /resources/forms`);
  const json = await res.json();
  return json.data as Record<string, FormTemplate[]>;
}

/** GET /resources/news */
export async function fetchNews(): Promise<NewsPost[]> {
  return apiFetch<NewsPost[]>('/resources/news', { next: { revalidate: 300 } });
}

/** GET /pages/{slug} — CMS page content for privacy, terms, cookies */
export async function fetchCmsPage(slug: string): Promise<CmsPage> {
  return apiFetch<CmsPage>(`/pages/${slug}`, { next: { revalidate: 300 } });
}

// ─────────────────────────────────────────────────────────────────────────────
// Contact form submission — POST /contact
// ─────────────────────────────────────────────────────────────────────────────

export interface ContactFormPayload {
  first_name: string;
  email: string;
  phone?: string;
  message: string;
}

export interface ContactFormResult {
  ok: boolean;
  message?: string;
  errors?: Record<string, string[]>;
}

/**
 * POST /contact — submits the contact form.
 * Returns a result object the caller can use to update UI state.
 * This function is intentionally not cached (it is a write operation).
 */
export async function submitContactForm(
  payload: ContactFormPayload
): Promise<ContactFormResult> {
  const url = `${BASE_URL}/contact`;
  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
    },
    body: JSON.stringify(payload),
    cache: 'no-store',
  });

  if (res.status === 201) {
    const json = await res.json();
    return { ok: true, message: json.data.message };
  }

  if (res.status === 422) {
    const json = await res.json();
    return { ok: false, errors: json.messages };
  }

  return { ok: false, message: 'An unexpected error occurred. Please try again.' };
}
```

---

## 7. `src/app/layout.tsx` — Root Layout

The layout is the most critical file because it supplies data to every shared component. In the static version, the layout is a simple synchronous function. After migration, it becomes an `async` Server Component that fetches site settings and navigation once per request (or per ISR interval) and passes the data down as props.

The key insight is that Next.js `fetch` deduplicates identical requests within a single render cycle. If any page also calls `fetchSiteSettings()` or `fetchNavigation()`, it will hit the cache rather than issuing a second network request to the API.

```tsx
// src/app/layout.tsx
import type { Metadata } from 'next';
import { Inter } from 'next/font/google';
import './globals.css';
import Navbar from '@/components/Navbar';
import Footer from '@/components/Footer';
import { fetchSiteSettings, fetchNavigation } from '@/lib/api';
import type { SiteSettings, Navigation } from '@/types/api';

const inter = Inter({
  subsets: ['latin'],
  variable: '--font-inter',
});

// generateMetadata replaces the static `metadata` export.
// It fetches site settings so the title and description are admin-controlled.
export async function generateMetadata(): Promise<Metadata> {
  try {
    const site = await fetchSiteSettings();
    return {
      title: site.meta_title,
      description: site.meta_description,
    };
  } catch {
    // Fall back to the original static values if the API is unreachable at build time.
    return {
      title: 'Greenland Business & Compliance',
      description: 'Professional Advisory, Accounting, and Regulatory solutions in Bangladesh',
    };
  }
}

export default async function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  // Fetch both data sources in parallel. If either fails (e.g. during
  // first build before the backend is live), fall back to empty defaults
  // so the app still renders structurally.
  let site: SiteSettings | null = null;
  let nav: Navigation | null = null;

  try {
    [site, nav] = await Promise.all([fetchSiteSettings(), fetchNavigation()]);
  } catch {
    // Errors are silently swallowed here; components render their fallback state.
    // In production, these errors will surface in server logs.
  }

  return (
    <html lang="en" className={inter.variable} suppressHydrationWarning>
      <body>
        <Navbar site={site} navItems={nav?.header ?? []} />
        {children}
        <Footer site={site} nav={nav} />
      </body>
    </html>
  );
}
```

---

## 8. `src/components/Navbar.tsx`

The Navbar component keeps its entire existing structure: the two-row design with a dark top bar and white main nav, the mobile drawer, all Lucide icons, all Tailwind classes, the `usePathname` active-link logic, and the `isMenuOpen` state. The only changes are:

1. The component now accepts `site` and `navItems` props instead of having them hard-coded.
2. Hard-coded strings (address, hours, phone, logo path) are replaced with values from the `site` prop.
3. The static `navLinks` array is replaced with the `navItems` prop.
4. All existing fallback values match the original hard-coded strings exactly, so the component renders identically when the API is unreachable.

```tsx
// src/components/Navbar.tsx
'use client';

import { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { usePathname } from 'next/navigation';
import { MapPin, Clock, Phone, Search, Menu, X } from 'lucide-react';
import type { SiteSettings, NavItem } from '@/types/api';

// ─────────────────────────────────────────────────────────────────────────────
// Props
// ─────────────────────────────────────────────────────────────────────────────

interface NavbarProps {
  site: SiteSettings | null;
  navItems: NavItem[];
}

export default function Navbar({ site, navItems }: NavbarProps) {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const pathname = usePathname();

  // ── Resolved values with fallbacks matching the original static content ──
  const logoUrl    = site?.logo_url    ?? '/logo/gc.png';
  const address    = site?.address     ?? 'Bottola Bazar, Bhakurta, Savar, Dhaka-1313.';
  const hours      = site?.business_hours ?? 'Mon to Sat 8 am to 10 pm | Sunday CLOSED';
  const phone      = site?.primary_phone  ?? '+8801987-644603';

  // When navItems is empty (API unreachable), fall back to the original static links
  // so navigation still works during a backend outage.
  const links: NavItem[] =
    navItems.length > 0
      ? navItems
      : [
          { id: 1, label: 'Home',         href: '/' },
          { id: 2, label: 'Services',     href: '/services' },
          { id: 3, label: 'Case Studies', href: '/case-studies' },
          { id: 4, label: 'About Us',     href: '/about' },
          { id: 5, label: 'Contact US',   href: '/contact' },
          { id: 6, label: 'Resources',    href: '/resources' },
        ];

  return (
    // ── Outer wrapper — unchanged from static version ──
    <div className="fixed top-0 left-0 w-full z-[1000]">

      {/* ── Top bar — unchanged layout, data from props ── */}
      <div className="hidden md:block bg-[#222] text-white text-xs py-2">
        <div className="max-w-[1200px] mx-auto px-4 flex justify-between items-center">
          <div className="flex items-center gap-6 text-gray-300">
            <span className="flex items-center gap-1">
              <MapPin size={12} /> {address}
            </span>
            <span className="flex items-center gap-1">
              <Clock size={12} /> {hours}
            </span>
            <span className="flex items-center gap-1">
              <Phone size={12} />
              <a href={`tel:${phone.replace(/\s/g, '')}`} className="hover:text-white">
                {phone} (Call Now)
              </a>
            </span>
          </div>
          <button aria-label="Search" className="text-gray-300 hover:text-white">
            <Search size={16} />
          </button>
        </div>
      </div>

      {/* ── Main nav — unchanged layout ── */}
      <nav className="bg-white shadow-sm">
        <div className="max-w-[1200px] mx-auto px-4 flex items-center justify-between h-16">

          {/* Logo: falls back to local public/ path if logo_url is null */}
          <Link href="/">
            <Image
              src={logoUrl}
              alt="Greenland Business & Compliance"
              width={250}
              height={60}
              priority
              className="object-contain"
              style={{ width: '180px' }}
              // The inline style is overridden to 250px on md+ by Tailwind via className below
            />
          </Link>

          {/* Desktop nav — unchanged */}
          <ul className="hidden lg:flex items-center gap-8">
            {links.map((link) => (
              <li key={link.id}>
                <Link
                  href={link.href}
                  className={`text-xs font-bold uppercase tracking-wide transition-colors ${
                    pathname === link.href
                      ? 'text-primary'
                      : 'text-gray-800 hover:text-primary'
                  }`}
                >
                  {link.label}
                </Link>
              </li>
            ))}
          </ul>

          {/* Mobile hamburger — unchanged */}
          <button
            className="lg:hidden"
            onClick={() => setIsMenuOpen(!isMenuOpen)}
            aria-label="Toggle Menu"
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>

        {/* Mobile drawer — unchanged layout, data from props */}
        {isMenuOpen && (
          <div className="lg:hidden absolute w-full bg-white border-t shadow-lg">
            <ul className="px-4 py-2">
              {links.map((link) => (
                <li key={link.id}>
                  <Link
                    href={link.href}
                    className={`block py-3 text-sm font-semibold ${
                      pathname === link.href ? 'text-primary' : 'text-gray-800'
                    }`}
                    onClick={() => setIsMenuOpen(false)}
                  >
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
            {/* Mobile contact footer — unchanged */}
            <div className="px-4 py-3 border-t text-xs text-gray-500">
              <p className="flex items-center gap-1 mb-1">
                <Phone size={12} /> {phone}
              </p>
              <p className="flex items-center gap-1">
                <MapPin size={12} /> Savar, Dhaka-1313
              </p>
            </div>
          </div>
        )}
      </nav>
    </div>
  );
}
```

**Implementation note on the logo `width` prop:** The original static Navbar sets `style={{ width: '180px' }}` on small screens and uses a Tailwind `md:` override. To preserve that exact behaviour, keep the inline style as shown and add `className="md:!w-[250px]"` to the Image element if needed, or keep the original approach that was in the static source.

---

## 9. `src/components/Footer.tsx`

The Footer follows the same pattern as Navbar: the entire JSX structure, every grid column, every Lucide icon mapping, all Tailwind classes, and the CTA row stay unchanged. The difference is that string literals and hard-coded arrays become values from the `site` and `nav` props.

The social link platform-to-icon mapping that was implicit in the static code (`Users` for LinkedIn, `Share2` for Facebook, `Globe` for Twitter, `Camera` for Instagram) is now an explicit map keyed by platform string. This is necessary because the API returns platform names as strings.

```tsx
// src/components/Footer.tsx
'use client';

import Link from 'next/link';
import Image from 'next/image';
import {
  MapPin, Phone, Mail, Share2, Globe, Users, Camera,
  ArrowRight, MessageSquare, Youtube,
} from 'lucide-react';
import type { SiteSettings, Navigation, SocialLink } from '@/types/api';

// ─────────────────────────────────────────────────────────────────────────────
// Platform → Lucide icon map (preserves original icon assignments)
// ─────────────────────────────────────────────────────────────────────────────

const platformIcon: Record<string, React.ReactNode> = {
  linkedin:  <Users   size={16} />,
  facebook:  <Share2  size={16} />,
  twitter:   <Globe   size={16} />,
  instagram: <Camera  size={16} />,
  youtube:   <Youtube size={16} />,
  whatsapp:  <MessageSquare size={16} />,
};

// ─────────────────────────────────────────────────────────────────────────────
// Props
// ─────────────────────────────────────────────────────────────────────────────

interface FooterProps {
  site: SiteSettings | null;
  nav: Navigation | null;
}

export default function Footer({ site, nav }: FooterProps) {

  // ── Resolved values with fallbacks matching the original static content ──
  const logoUrl       = site?.logo_url    ?? '/logo/gc.png';
  const description   = site?.footer_description
    ?? 'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. Building sustainable business foundations in Bangladesh since 1985.';
  const address       = site?.address     ?? 'Bottola Bazar, Bhakurta, Savar, Dhaka-1313, Bangladesh.';
  const phone         = site?.primary_phone ?? '+8801987-644603';
  const email         = site?.primary_email ?? 'contact@greenlandcompliance.com';
  const copyright     = site?.copyright_text ?? `© ${new Date().getFullYear()} Greenland Business & Compliance. All Rights Reserved.`;
  const socialLinks   = site?.social_links ?? [];
  const footerCtaTitle = site?.footer_cta_title ?? 'Ready to take your business to the next level?';
  const footerCtaText = site?.footer_cta_text
    ?? 'Our expert consultants are ready to help you navigate the complexities of compliance and growth in Bangladesh.';
  const footerCtaButtonLabel = site?.footer_cta_button_label ?? 'Request a Free Quote';
  const footerCtaButtonHref = site?.footer_cta_button_href ?? '/contact';

  // Navigation column fallbacks
  const quickLinks    = nav?.footer_quick    ?? [];
  const serviceLinks  = nav?.footer_services ?? [];
  const policyLinks   = nav?.footer_policy   ?? [];

  return (
    <footer style={{ background: '#1a1a1a', color: 'white' }} className="pt-20 pb-10">
      <div className="max-w-[1200px] mx-auto px-4">

        {/* ── Main grid: 1col → 2col → 4col (unchanged) ── */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

          {/* Column 1: Company info */}
          <div>
            <Image
              src={logoUrl}
              alt="Greenland Business & Compliance"
              width={200}
              height={50}
              className="object-contain brightness-0 invert mb-4"
            />
            <p className="text-gray-400 text-sm leading-relaxed mb-6">
              {description}
            </p>
            {/* Social icon row — rendered from API social_links */}
            <div className="flex gap-3">
              {socialLinks.length > 0
                ? socialLinks.map((link: SocialLink, idx: number) => (
                    <a
                      key={link.id ?? idx}
                      href={link.url}
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label={link.platform}
                      className="w-9 h-9 rounded-full flex items-center justify-center
                                 bg-gray-700 hover:bg-primary transition-colors"
                    >
                      {platformIcon[link.platform] ?? <Globe size={16} />}
                    </a>
                  ))
                // Fallback: render the original four placeholder icons
                : (
                  <>
                    {[
                      { icon: <Users   size={16} />, label: 'LinkedIn' },
                      { icon: <Share2  size={16} />, label: 'Facebook' },
                      { icon: <Globe   size={16} />, label: 'Twitter' },
                      { icon: <Camera  size={16} />, label: 'Instagram' },
                    ].map(({ icon, label }) => (
                      <button
                        key={label}
                        aria-label={label}
                        className="w-9 h-9 rounded-full flex items-center justify-center
                                   bg-gray-700 hover:bg-primary transition-colors"
                      >
                        {icon}
                      </button>
                    ))}
                  </>
                )
              }
            </div>
          </div>

          {/* Column 2: Quick Links — from nav.footer_quick */}
          <div>
            <h4 className="text-white font-bold uppercase text-sm mb-6 tracking-wider">
              Quick Links
            </h4>
            <ul className="space-y-3">
              {(quickLinks.length > 0 ? quickLinks : [
                { id: 1, label: 'Home',         href: '/' },
                { id: 2, label: 'About Us',     href: '/about' },
                { id: 3, label: 'Our Services', href: '/services' },
                { id: 4, label: 'Case Studies', href: '/case-studies' },
                { id: 5, label: 'Resources',    href: '/resources' },
                { id: 6, label: 'Contact Us',   href: '/contact' },
              ]).map((item) => (
                <li key={item.id}>
                  <Link
                    href={item.href}
                    className="text-gray-400 hover:text-primary text-sm flex items-center gap-2 transition-colors"
                  >
                    <ArrowRight size={14} /> {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Column 3: Services — from nav.footer_services */}
          <div>
            <h4 className="text-white font-bold uppercase text-sm mb-6 tracking-wider">
              Our Services
            </h4>
            <ul className="space-y-3">
              {(serviceLinks.length > 0 ? serviceLinks : [
                { id: 1, label: 'Business Advisory',        href: '/services' },
                { id: 2, label: 'Audit & Assurance',        href: '/services' },
                { id: 3, label: 'Taxation Services',        href: '/services' },
                { id: 4, label: 'Regulatory Compliance',    href: '/services' },
                { id: 5, label: 'Human Capital Management', href: '/services' },
                { id: 6, label: 'Strategy Consulting',      href: '/services' },
              ]).map((item) => (
                <li key={item.id}>
                  <Link
                    href={item.href}
                    className="text-gray-400 hover:text-primary text-sm flex items-center gap-2 transition-colors"
                  >
                    <ArrowRight size={14} /> {item.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Column 4: Contact info */}
          <div>
            <h4 className="text-white font-bold uppercase text-sm mb-6 tracking-wider">
              Contact Us
            </h4>
            <ul className="space-y-4 text-gray-400 text-sm">
              <li className="flex gap-3">
                <MapPin size={16} className="text-primary mt-0.5 shrink-0" />
                <span>{address}</span>
              </li>
              <li className="flex gap-3">
                <Phone size={16} className="text-primary shrink-0" />
                <a href={`tel:${phone.replace(/\s/g, '')}`} className="hover:text-primary transition-colors">
                  {phone}
                </a>
              </li>
              <li className="flex gap-3">
                <Mail size={16} className="text-primary shrink-0" />
                <a href={`mailto:${email}`} className="hover:text-primary transition-colors">
                  {email}
                </a>
              </li>
            </ul>
          </div>
        </div>

        {/* ── CTA row — text and button are still static; can be made dynamic later ── */}
        <div
          className="mb-8 p-8 border-l-4 border-primary"
          style={{ background: '#222' }}
        >
          <div className="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
              <h3 className="text-white font-bold text-lg mb-1">
                {footerCtaTitle}
              </h3>
              <p className="text-gray-400 text-sm">
                {footerCtaText}
              </p>
            </div>
            <Link
              href={footerCtaButtonHref}
              className="shrink-0 bg-primary hover:bg-green-600 text-white
                         font-semibold px-6 py-3 rounded text-sm transition-colors whitespace-nowrap"
            >
              {footerCtaButtonLabel}
            </Link>
          </div>
        </div>

        {/* ── Bottom bar: copyright + policy links ── */}
        <div className="border-t border-gray-700 pt-6 flex flex-col md:flex-row
                        items-center justify-between gap-4 text-xs text-gray-500">
          <p>{copyright}</p>
          <div className="flex gap-6">
            {(policyLinks.length > 0 ? policyLinks : [
              { id: 1, label: 'Privacy Policy',   href: '/privacy' },
              { id: 2, label: 'Terms of Service', href: '/terms' },
              { id: 3, label: 'Cookie Settings',  href: '/cookies' },
            ]).map((link) => (
              <Link
                key={link.id}
                href={link.href}
                className="hover:text-primary transition-colors"
              >
                {link.label}
              </Link>
            ))}
          </div>
        </div>
      </div>
    </footer>
  );
}
```

---

## 10. `src/components/Hero.tsx`

The Hero component must remain a Client Component because it uses `useState` and `useEffect` for the auto-advancing image slider with a 5000ms interval. However, the initial data (slide array, headline, CTAs) now arrives as props from the parent Server Component (`page.tsx`) rather than being hard-coded.

The component accepts a `data` prop of type `HeroData | null`. When `null` (API unreachable at build time), it renders the original static content exactly as before.

```tsx
// src/components/Hero.tsx
'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { ArrowRight } from 'lucide-react';
import type { HeroData } from '@/types/api';

interface HeroProps {
  data: HeroData | null;
}

export default function Hero({ data }: HeroProps) {
  const [currentSlide, setCurrentSlide] = useState(0);

  // ── Fallback values match the original static content exactly ──
  const headline1 = data?.headline_line1 ?? 'Your Vision, Our Compliance.';
  const headline2 = data?.headline_line2 ?? 'Building Sustainable Business Foundations in Bangladesh';
  const paragraph = data?.paragraph ??
    'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. We handle the complexity so you can lead with confidence.';
  const cta1Label = data?.cta1_label ?? 'Book a Consultation';
  const cta1Href  = data?.cta1_href  ?? '/contact';
  const cta2Label = data?.cta2_label ?? 'Explore Our Services';
  const cta2Href  = data?.cta2_href  ?? '/services';

  // When no API slides are available, fall back to the original local assets.
  // These local paths are still present in public/ during the transition period
  // until the backend team confirms all images have been migrated to storage.
  const slides = data?.slides && data.slides.length > 0
    ? data.slides
    : [
        { id: 1, image_url: '/Background Img/hero1.jpg',  alt_text: 'Hero slide 1', sort_order: 1 },
        { id: 2, image_url: '/Background Img/hero2..jpg', alt_text: 'Hero slide 2', sort_order: 2 },
        { id: 3, image_url: '/Background Img/hero3.jpg',  alt_text: 'Hero slide 3', sort_order: 3 },
      ];

  // Auto-advance slider — unchanged from the static version
  useEffect(() => {
    if (slides.length <= 1) return;
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 5000);
    return () => clearInterval(timer);
  }, [slides.length]);

  return (
    // ── Outer container — unchanged from static version ──
    <section
      className="relative h-screen text-white overflow-hidden"
      style={{ minHeight: '600px' }}
    >
      {/* Background slides — unchanged rendering logic */}
      {slides.map((slide, index) => (
        <div
          key={slide.id}
          className="absolute inset-0 transition-opacity"
          style={{
            opacity: index === currentSlide ? 1 : 0,
            transitionDuration: '1500ms',
          }}
        >
          <Image
            src={slide.image_url}
            alt={slide.alt_text}
            fill
            quality={75}
            className="object-cover"
            priority={index === 0}
          />
        </div>
      ))}

      {/* Overlay — unchanged */}
      <div className="absolute inset-0 bg-gradient-to-r from-black/80 to-black/40 bg-[#79b940]/10" />

      {/* Content — unchanged layout, data from props */}
      <div className="relative z-10 h-full flex items-center">
        <div className="max-w-[1200px] mx-auto px-4 w-full">
          <div className="max-w-2xl">
            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
              {headline1}
              <br />
              {headline2}
            </h1>
            <p className="text-lg md:text-xl text-gray-200 mb-8 leading-relaxed">
              {paragraph}
            </p>
            <div className="flex flex-col sm:flex-row gap-4">
              <Link
                href={cta1Href}
                className="inline-flex items-center justify-center bg-primary
                           hover:bg-green-600 text-white font-semibold px-8 py-4
                           rounded transition-colors"
              >
                {cta1Label}
              </Link>
              <Link
                href={cta2Href}
                className="inline-flex items-center gap-2 text-white
                           hover:text-primary font-semibold py-4 transition-colors"
              >
                {cta2Label} <ArrowRight size={20} />
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
```

---

## 11. `src/app/page.tsx` — Home Page

The home page becomes an `async` Server Component. Its only job is to fetch the hero data and pass it to the `<Hero>` component as a prop. Everything else about the page is unchanged.

```tsx
// src/app/page.tsx
import Hero from '@/components/Hero';
import { fetchHero } from '@/lib/api';
import type { HeroData } from '@/types/api';

export default async function HomePage() {
  let heroData: HeroData | null = null;

  try {
    heroData = await fetchHero();
  } catch {
    // If the API is unreachable, Hero renders with fallback static content.
  }

  return (
    <main>
      <Hero data={heroData} />
      {/* Other sections will be added here later — same comment as original */}
    </main>
  );
}
```

---

## 12. `src/app/services/page.tsx`

The services page is already a Client Component (`'use client'`) because it uses `useState` for `activeTab`. After migration, it additionally fetches the service categories and their services from the API on mount.

The changes are: `activeTab` is initialised to `null` (or the slug of the first category once data loads) instead of `'advisory'`; the static `tabs` array and `serviceData` object are replaced with the API response; the tab rendering loop and service card rendering loop remain identical in JSX structure.

The CSS module (`services.module.css`) is **not touched** — all class names remain the same.

```tsx
// src/app/services/page.tsx
'use client';

import { useState, useEffect } from 'react';
import { fetchServices } from '@/lib/api';
import type { ServiceCategory, ServiceItem } from '@/types/api';
import styles from './services.module.css';

export default function ServicesPage() {
  const [categories, setCategories] = useState<ServiceCategory[]>([]);
  const [activeTab, setActiveTab] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(false);

  useEffect(() => {
    fetchServices()
      .then((data) => {
        setCategories(data);
        // Set initial active tab to the first category's slug,
        // matching the original behaviour of defaulting to 'advisory'.
        if (data.length > 0 && !activeTab) {
          setActiveTab(data[0].slug);
        }
        setLoading(false);
      })
      .catch(() => {
        setError(true);
        setLoading(false);
      });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  // ── Derive the displayed services from the active tab ──
  const activeCategory = categories.find((c) => c.slug === activeTab);
  const services: ServiceItem[] = activeCategory?.services ?? [];

  // ── Loading state — minimal, preserves layout height ──
  if (loading) {
    return (
      <div className={styles.container}>
        <div className="text-center py-20 text-gray-400">Loading services…</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className={styles.container}>
        <div className="text-center py-20 text-red-500">
          Could not load services. Please try again later.
        </div>
      </div>
    );
  }

  return (
    <div className={styles.container}>

      {/* ── Tabs — generated from API categories ── */}
      <div className={styles.tabs}>
        {categories.map((cat) => (
          <button
            key={cat.id}
            onClick={() => setActiveTab(cat.slug)}
            className={`${styles.tab} ${activeTab === cat.slug ? styles.active : ''}`}
          >
            {cat.label}
          </button>
        ))}
      </div>

      {/* ── Service grid — JSX structure unchanged ── */}
      <div className={styles.serviceGrid}>
        {services.map((service) => (
          <div key={service.id} className={styles.serviceItem}>
            {service.badge && (
              <span
                className={`${styles.badge} ${service.badge === 'SPECIAL' ? styles.special : ''}`}
              >
                {service.badge}
              </span>
            )}
            <div className={styles.headerRow}>
              <div className={styles.titleWrapper}>
                <h3 className={styles.title}>{service.title}</h3>
                <div className={styles.dots} />
              </div>
              <span className={styles.price}>{service.price}</span>
            </div>
            <p className={styles.description}>{service.description}</p>
          </div>
        ))}
      </div>

    </div>
  );
}
```

**Important note on CSS module class names:** The class names used above (`container`, `tabs`, `tab`, `active`, `serviceGrid`, `serviceItem`, `badge`, `special`, `headerRow`, `titleWrapper`, `title`, `dots`, `price`, `description`) are the exact class names currently defined in `services.module.css`. Do not rename CSS module classes during the dynamic migration.

---

## 13. `src/app/case-studies/page.tsx`

The case studies page is already `'use client'`. After migration it fetches two endpoints in parallel on mount: the case studies list and the testimonials filtered to `case_studies` context. The sidebar's company presentation download button is wired to the `company_presentation_url` from the site settings fetch.

The image rendering logic is changed: when `image_url` is non-null, it renders a `next/image` `<Image>` component; when `image_url` is null, it renders the same `img here` placeholder that exists today. This means real images appear automatically once they are uploaded through the admin panel, with no further code changes needed.

The CSS module (`case-studies.module.css`) is **not touched**.

```tsx
// src/app/case-studies/page.tsx
'use client';

import { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { Play, Quote } from 'lucide-react';
import { fetchCaseStudies, fetchTestimonials, fetchSiteSettings } from '@/lib/api';
import type { CaseStudy, Testimonial, SiteSettings } from '@/types/api';
import styles from './case-studies.module.css';

export default function CaseStudiesPage() {
  const [caseStudies, setCaseStudies] = useState<CaseStudy[]>([]);
  const [testimonials, setTestimonials] = useState<Testimonial[]>([]);
  const [site, setSite] = useState<SiteSettings | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      fetchCaseStudies(),
      fetchTestimonials('case_studies'),
      fetchSiteSettings(),
    ])
      .then(([studies, testimonialData, siteData]) => {
        setCaseStudies(studies);
        setTestimonials(testimonialData);
        setSite(siteData);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, []);

  const presentationUrl = site?.company_presentation_url ?? null;

  if (loading) {
    return (
      <div className={styles.container}>
        <div className="text-center py-20 text-gray-400">Loading case studies…</div>
      </div>
    );
  }

  return (
    <div className={styles.container}>
      <div className={styles.layout}>

        {/* ── Main content: case study grid ── */}
        <main className={styles.mainContent}>
          <div className={styles.grid}>
            {caseStudies.map((study) => (
              <div key={study.id} className={styles.caseItem}>
                {/* Image or placeholder — same visual as original when image_url is null */}
                <div className={styles.imageWrapper}>
                  {study.image_url ? (
                    <Image
                      src={study.image_url}
                      alt={study.title}
                      fill
                      className="object-cover rounded-lg"
                    />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold italic">
                      img here
                    </div>
                  )}
                </div>
                <p className={styles.category}>{study.category.name}</p>
                <h3 className={styles.title}>{study.title}</h3>
              </div>
            ))}
          </div>
        </main>

        {/* ── Sidebar: unchanged structure, data from API ── */}
        <aside className={styles.sidebar}>

          {/* Presentation download — wired to real URL */}
          {presentationUrl ? (
            <a
              href={presentationUrl}
              target="_blank"
              rel="noopener noreferrer"
              className={styles.downloadBox}
            >
              <div className={styles.iconCircle}>
                <Play size={20} className="fill-white text-white ml-1" />
              </div>
              <div>
                <span className={styles.downloadLabel}>Download</span>
                <div className={styles.downloadTitle}>Company presentation</div>
              </div>
            </a>
          ) : (
            <div className={styles.downloadBox} aria-disabled="true">
              <div className={styles.iconCircle}>
                <Play size={20} className="fill-white text-white ml-1" />
              </div>
              <div>
                <span className={styles.downloadLabel}>Download</span>
                <div className={styles.downloadTitle}>Company presentation</div>
              </div>
            </div>
          )}

          {/* Help box — unchanged static content */}
          <div className={styles.helpBox}>
            <h4 className={styles.helpTitle}>How can we help you?</h4>
            <p className={styles.helpDesc}>Contact us for your query or submit a business inquiry online.</p>
            <Link href="/contact" className={styles.contactBtn}>CONTACT US</Link>
          </div>

          {/* Testimonials — from API */}
          {testimonials.map((t) => (
            <div key={t.id} className={styles.testimonial}>
              <p className={styles.quoteText}>&ldquo;{t.quote}&rdquo;</p>
              <div className={styles.quoteIcon}>
                <Quote size={48} fill="currentColor" />
              </div>
              <div className={styles.authorRow}>
                <div className={styles.avatar}>
                  {t.avatar_url ? (
                    <Image
                      src={t.avatar_url}
                      alt={t.author}
                      width={48}
                      height={48}
                      className="w-full h-full object-cover"
                    />
                  ) : (
                    <div className="w-full h-full bg-gray-200" />
                  )}
                </div>
                <div>
                  <p className={styles.authorName}>{t.author}</p>
                  <p className={styles.authorRole}>{t.role}</p>
                </div>
              </div>
            </div>
          ))}
        </aside>

      </div>
    </div>
  );
}
```

---

## 14. `src/app/about/page.tsx`

The about page is already `'use client'` because of the `activeView` state that controls which of six content panels is displayed. After migration, a single `useEffect` call fetches `GET /about`, which returns the entire page's content in one response, and stores it in a single `aboutData` state variable. Each view panel reads from the relevant sub-object of that state.

The about page has the most content complexity, so each view section is documented below.

### 14.1 Icon Map For Approach Cards

The approach cards returned by the API include an `icon` field that contains a Lucide icon name as a string (e.g. `"Plane"`, `"TrendingUp"`). Because Lucide icons are React components that must be imported by name at build time, a static map is needed. The map includes all six icons used in the seeded data plus a fallback.

```ts
// Add this near the top of about/page.tsx, after imports
import {
  Plane, TrendingUp, ShoppingCart, Building2, Zap, Truck,
  ChevronRight, Play,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';

const iconMap: Record<string, LucideIcon> = {
  Plane,
  TrendingUp,
  ShoppingCart,
  Building2,
  Zap,
  Truck,
};

// Usage in JSX:
// const IconComponent = iconMap[card.icon] ?? Building2;
// <IconComponent size={24} />
```

If the admin later adds a new approach card with an icon not in this map, the fallback `Building2` icon is used. To support additional icons, add them to both the import list and the `iconMap` object.

### 14.2 Full `about/page.tsx` Implementation

```tsx
// src/app/about/page.tsx
'use client';

import { useState, useEffect } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import {
  ChevronRight, Play,
  Plane, TrendingUp, ShoppingCart, Building2, Zap, Truck,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';
import { fetchAbout } from '@/lib/api';
import type { AboutData } from '@/types/api';

// ─────────────────────────────────────────────────────────────────────────────
// Icon map for approach cards — add more Lucide icons here as needed
// ─────────────────────────────────────────────────────────────────────────────
const iconMap: Record<string, LucideIcon> = {
  Plane, TrendingUp, ShoppingCart, Building2, Zap, Truck,
};

type AboutView = 'overview' | 'approach' | 'achievement' | 'partners' | 'team' | 'faq';

const sidebarMenu: { id: AboutView; label: string }[] = [
  { id: 'overview',    label: 'Company overview' },
  { id: 'approach',    label: 'Our approach' },
  { id: 'achievement', label: 'Our Achievement' },
  { id: 'partners',    label: 'Partners' },
  { id: 'team',        label: 'Our Team' },
  { id: 'faq',         label: 'FAQ' },
];

export default function AboutPage() {
  const [activeView, setActiveView] = useState<AboutView>('overview');
  const [openFaqIdx, setOpenFaqIdx] = useState<number>(0);
  const [aboutData, setAboutData] = useState<AboutData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAbout()
      .then((data) => {
        setAboutData(data);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, []);

  // ── Shorthand accessors ──
  const d = aboutData;

  // ── Loading ──
  if (loading) {
    return (
      <div className="max-w-[1200px] mx-auto px-4 pt-[120px] pb-20">
        <div className="text-center py-20 text-gray-400">Loading…</div>
      </div>
    );
  }

  // ── Resolved hero values with fallbacks ──
  const heroHeading1 = d?.hero.heading_line1 ?? 'Workshops';
  const heroHeading2 = d?.hero.heading_line2 ?? 'that awesome!';
  const heroParagraph = d?.hero.paragraph ??
    'We are a company that offers design and build services for you from initial sketches to the final construction.';
  const heroImageUrl  = d?.hero.image_url ?? '/about/hero.png';
  const heroCtaLabel  = d?.hero.cta_label ?? 'get a quote';
  const heroCtaHref   = d?.hero.cta_href  ?? '/contact';
  const bannerLabel   = d?.banner_label   ?? 'About Us';
  const presentationUrl = d?.company_presentation_url ?? null;

  return (
    <div style={{ paddingTop: '120px', paddingBottom: '80px', background: '#fff' }}>

      {/* ── Top banner — unchanged layout, label from API ── */}
      <div style={{ height: '60px' }} className="flex mb-8">
        <div className="px-8 flex items-center" style={{ background: '#333' }}>
          <span className="text-primary font-bold text-sm uppercase tracking-wider">
            {bannerLabel}
          </span>
        </div>
        <div className="flex-1" style={{ background: '#79b940' }} />
      </div>

      <div className="max-w-[1200px] mx-auto px-4">

        {/* ── Always-visible About Hero section ── */}
        <div
          className="rounded mb-8 overflow-hidden"
          style={{ background: '#79b940', minHeight: '350px' }}
        >
          <div className="flex flex-col md:flex-row h-full">
            <div className="flex-1 p-10 flex flex-col justify-center text-white">
              <h1 className="text-4xl font-bold mb-4">
                {heroHeading1}<br />{heroHeading2}
              </h1>
              <p className="text-white/80 mb-6 leading-relaxed">{heroParagraph}</p>
              <Link
                href={heroCtaHref}
                className="inline-flex items-center gap-2 bg-white text-primary
                           font-semibold px-6 py-3 rounded self-start hover:bg-gray-50 transition-colors"
              >
                {heroCtaLabel} <ChevronRight size={18} />
              </Link>
              {/* Two decorative white dots — unchanged */}
              <div className="flex gap-2 mt-6">
                <div className="w-2 h-2 rounded-full bg-white/60" />
                <div className="w-2 h-2 rounded-full bg-white/60" />
              </div>
            </div>
            <div className="relative w-full md:w-80 h-64 md:h-auto">
              <Image
                src={heroImageUrl}
                alt="Laptop with dashboard"
                fill
                className="object-contain"
              />
            </div>
          </div>
        </div>

        {/* ── Two-column layout: main content + sidebar ── */}
        <div className="flex flex-col lg:flex-row gap-8">

          {/* ── Main content (72%) ── */}
          <div className="flex-1">
            {activeView === 'overview' && <OverviewView d={d} />}
            {activeView === 'approach' && <ApproachView d={d} />}
            {activeView === 'achievement' && <AchievementView d={d} />}
            {activeView === 'partners' && <PartnersView d={d} />}
            {activeView === 'team' && <TeamView d={d} />}
            {activeView === 'faq' && (
              <FaqView d={d} openIdx={openFaqIdx} setOpenIdx={setOpenFaqIdx} />
            )}
          </div>

          {/* ── Sidebar (28%) ── */}
          <div className="lg:w-[28%] space-y-6">

            {/* Sidebar menu */}
            <div className="bg-white shadow rounded overflow-hidden">
              {sidebarMenu.map((item) => (
                <button
                  key={item.id}
                  onClick={() => setActiveView(item.id)}
                  className={`w-full text-left px-6 py-4 text-sm font-medium border-b
                    last:border-0 transition-colors
                    ${activeView === item.id
                      ? 'bg-white text-primary border-l-4 border-l-primary shadow-sm'
                      : 'text-gray-500 hover:bg-white hover:text-primary'
                    }`}
                >
                  {item.label}
                </button>
              ))}
            </div>

            {/* Presentation download — wired to company_presentation_url from GET /about */}
            {/* GET /about includes company_presentation_url so this button is functional */}
            <div className="bg-gray-800 text-white rounded p-4">
              {presentationUrl ? (
                <a
                  href={presentationUrl}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center gap-2 text-sm font-semibold mb-1"
                >
                  <Play size={14} /> Download
                </a>
              ) : (
                <div
                  aria-disabled="true"
                  className="flex items-center gap-2 text-sm font-semibold mb-1 opacity-60"
                >
                  <Play size={14} /> Download
                </div>
              )}
              <p className="text-xs text-gray-400">Company presentation</p>
            </div>

            {/* Help box */}
            <div className="bg-primary text-white rounded p-6">
              <h4 className="font-bold mb-2">How can we help you?</h4>
              <p className="text-sm text-white/80 mb-4">
                Contact us for your query or submit a business inquiry online.
              </p>
              <Link
                href="/contact"
                className="inline-block bg-white text-primary font-semibold
                           text-xs px-4 py-2 rounded hover:bg-gray-50 transition-colors"
              >
                Contact Us
              </Link>
            </div>

            {/* Sidebar testimonials */}
            {(d?.testimonials ?? []).map((t) => (
              <div key={t.id} className="bg-white rounded shadow p-5">
                <p className="text-gray-600 text-sm italic mb-4">&ldquo;{t.quote}&rdquo;</p>
                <div className="flex items-center gap-3">
                  {t.avatar_url ? (
                    <Image
                      src={t.avatar_url}
                      alt={t.author}
                      width={40}
                      height={40}
                      className="rounded-full object-cover"
                    />
                  ) : (
                    <div className="w-10 h-10 rounded-full bg-gray-200" />
                  )}
                  <div>
                    <p className="text-sm font-semibold text-gray-800">{t.author}</p>
                    <p className="text-xs text-gray-500">{t.role}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* ── Footer CTA — unchanged layout, data from API ── */}
        <div className="mt-12 bg-primary text-white rounded p-8 flex flex-col
                        sm:flex-row items-center justify-between gap-4">
          <p className="font-bold text-lg uppercase tracking-wide">
            {d?.footer_cta.text ?? 'LOOKING FOR A FIRST-CLASS BUSINESS PLAN CONSULTANT?'}
          </p>
          <Link
            href={d?.footer_cta.button_href ?? '/contact'}
            className="inline-flex items-center gap-2 bg-white text-primary
                       font-semibold px-6 py-3 rounded hover:bg-gray-50 transition-colors whitespace-nowrap"
          >
            {d?.footer_cta.button_label ?? 'get a quote'} <ChevronRight size={18} />
          </Link>
        </div>

      </div>
    </div>
  );
}

// ─────────────────────────────────────────────────────────────────────────────
// Sub-components for each view — keep JSX structure identical to static version
// ─────────────────────────────────────────────────────────────────────────────

function OverviewView({ d }: { d: AboutData | null }) {
  const overview = d?.overview;
  return (
    <div>
      {/* Timeline */}
      <div className="mb-10">
        {(overview?.timeline ?? []).map((item, idx) => (
          <div key={item.id} className="flex gap-6 mb-8">
            <div className="shrink-0 w-16 text-center">
              <span className="text-primary font-bold text-lg">{item.year}</span>
              {idx < (overview?.timeline.length ?? 0) - 1 && (
                <div className="w-px h-full bg-gray-200 mx-auto mt-2" />
              )}
            </div>
            <div className="pb-4 border-b border-gray-100 flex-1">
              <h4 className="font-bold text-gray-800 mb-2">{item.title}</h4>
              <p className="text-gray-500 text-sm leading-relaxed">{item.description}</p>
            </div>
          </div>
        ))}
      </div>

      {/* Company overview */}
      <div className="mb-8">
        <h2 className="text-xl font-bold text-gray-800 uppercase tracking-wide mb-4">
          COMPANY OVERVIEW
        </h2>
        <p className="text-gray-600 text-sm leading-relaxed mb-4">{overview?.paragraph1}</p>
        <p className="text-gray-600 text-sm leading-relaxed mb-4">{overview?.paragraph2}</p>
        {overview?.callout && (
          <blockquote className="border-l-4 border-primary pl-4 text-gray-600 text-sm italic">
            {overview.callout}
          </blockquote>
        )}
      </div>

      {/* Mission */}
      <div className="mb-8">
        <h3 className="font-bold text-gray-800 mb-2">{overview?.mission_heading ?? 'Our mission'}</h3>
        <p className="text-gray-600 text-sm mb-3">{overview?.mission_intro}</p>
        <ul className="space-y-2">
          {(overview?.mission_bullets ?? []).map((b) => (
            <li key={b.id} className="flex gap-2 text-sm text-gray-600">
              <span className="text-primary">—</span> {b.text}
            </li>
          ))}
        </ul>
      </div>

      {/* How we work video */}
      {overview?.how_we_work_video_url && (
        <div>
          <h3 className="font-bold text-gray-800 mb-4">How we work</h3>
          <div className="aspect-video">
            <iframe
              src={overview.how_we_work_video_url}
              title="YouTube video player"
              className="w-full h-full rounded"
              allowFullScreen
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            />
          </div>
        </div>
      )}
    </div>
  );
}

function ApproachView({ d }: { d: AboutData | null }) {
  const approach = d?.approach;
  return (
    <div>
      <h2 className="text-xl font-bold uppercase tracking-wide text-gray-800 mb-6">
        OUR APPROACH
      </h2>
      <p className="text-gray-600 text-sm leading-relaxed mb-4">{approach?.intro1}</p>
      <p className="text-gray-600 text-sm leading-relaxed mb-8">{approach?.intro2}</p>

      {/* Approach cards grid — hex clip-path icons unchanged */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {(approach?.cards ?? []).map((card) => {
          const IconComponent = iconMap[card.icon] ?? Building2;
          return (
            <div key={card.id} className="group flex gap-4">
              <div
                className="shrink-0 w-12 h-12 flex items-center justify-center
                           border-2 border-gray-800 group-hover:border-primary
                           transition-colors text-gray-800 group-hover:text-primary"
                style={{ clipPath: 'polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%)' }}
              >
                <IconComponent size={20} />
              </div>
              <div>
                <h4 className="font-bold text-gray-800 mb-1 text-sm">{card.title}</h4>
                <p className="text-gray-500 text-xs leading-relaxed">{card.description}</p>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

function AchievementView({ d }: { d: AboutData | null }) {
  return (
    <div>
      <h2 className="text-xl font-bold uppercase tracking-wide text-gray-800 mb-6">
        OUR ACHIEVEMENT
      </h2>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        {(d?.achievements ?? []).map((cert) => (
          <div key={cert.id} className="group">
            <div
              className="aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden mb-3 relative"
            >
              {cert.image_url ? (
                <Image
                  src={cert.image_url}
                  alt={cert.title}
                  fill
                  className="object-cover group-hover:scale-105 transition-transform duration-300"
                />
              ) : (
                // Same placeholder as the broken images in the static version
                <div className="w-full h-full flex items-center justify-center text-gray-300 text-sm">
                  {cert.title}
                </div>
              )}
            </div>
            <h4 className="font-semibold text-gray-800 text-sm group-hover:text-primary
                           transition-colors border-b-2 border-transparent
                           group-hover:border-primary inline-block pb-0.5">
              {cert.title}
            </h4>
          </div>
        ))}
      </div>
    </div>
  );
}

function PartnersView({ d }: { d: AboutData | null }) {
  return (
    <div>
      <ul className="divide-y divide-gray-100">
        {(d?.partners ?? []).map((partner) => (
          <li key={partner.id} className="py-6">
            <div className="flex items-center justify-between mb-2">
              <h4 className="font-bold text-gray-800">{partner.name}</h4>
              <span className="text-xs text-gray-400">
                {partner.industry} {partner.location ? `· ${partner.location}` : ''}
              </span>
            </div>
            <hr className="border-gray-200 mb-3" />
            <p className="text-gray-500 text-sm leading-relaxed">{partner.description}</p>
          </li>
        ))}
      </ul>
    </div>
  );
}

function TeamView({ d }: { d: AboutData | null }) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
      {(d?.team ?? []).map((member) => (
        <div key={member.id} className="text-center">
          {member.image_url ? (
            <div className="relative w-24 h-24 rounded-full overflow-hidden mx-auto mb-4">
              <Image
                src={member.image_url}
                alt={member.name}
                fill
                className="object-cover"
              />
            </div>
          ) : (
            // Preserves the round "Image Frame" placeholder from the static version
            <div className="w-24 h-24 rounded-full bg-gray-200 mx-auto mb-4 flex
                            items-center justify-center text-gray-400 text-xs">
              Image Frame
            </div>
          )}
          <h4 className="font-bold text-gray-800">{member.name}</h4>
          <p className="text-primary text-sm mb-2">{member.role}</p>
          <p className="text-gray-500 text-xs leading-relaxed mb-3">
            {member.description}
          </p>
          {/* "view profile" button — unchanged; profile pages not yet implemented */}
          <button className="text-xs text-primary border border-primary rounded px-4 py-1
                             hover:bg-primary hover:text-white transition-colors">
            view profile
          </button>
        </div>
      ))}
    </div>
  );
}

function FaqView({
  d,
  openIdx,
  setOpenIdx,
}: {
  d: AboutData | null;
  openIdx: number;
  setOpenIdx: (idx: number) => void;
}) {
  return (
    <div>
      <h2 className="text-xl font-bold uppercase tracking-wide text-gray-800 mb-6">FAQ</h2>
      <div className="space-y-2">
        {(d?.faqs ?? []).map((faq, idx) => {
          const isOpen = openIdx === idx;
          return (
            <div key={faq.id} className="border border-gray-200 rounded">
              <button
                className="w-full flex justify-between items-center px-5 py-4 text-left
                           text-sm font-medium text-gray-800 hover:text-primary transition-colors"
                onClick={() => setOpenIdx(isOpen ? -1 : idx)}
              >
                {faq.question}
                {/* Uses "–" (en-dash) and "+" instead of the original mojibake */}
                <span className="text-primary font-bold text-lg shrink-0 ml-4">
                  {isOpen ? '–' : '+'}
                </span>
              </button>
              {/* Accordion expand — same CSS grid technique as the static version */}
              <div
                style={{
                  display: 'grid',
                  gridTemplateRows: isOpen ? '1fr' : '0fr',
                  opacity: isOpen ? 1 : 0,
                  transition: 'grid-template-rows 0.3s, opacity 0.3s',
                }}
              >
                <div className="overflow-hidden">
                  <p className="px-5 pb-4 text-sm text-gray-500 leading-relaxed">
                    {faq.answer}
                  </p>
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
```

---

## 15. `src/app/contact/page.tsx`

The contact page is the only page that was a pure Server Component (no `'use client'` directive) in the static version. After migration it must become a Client Component because:

1. The contact form now has controlled inputs and state for the submission flow.
2. The contact info is fetched on mount.

The entire visual structure — the three-column top grid with the image placeholder, Google map, and contact details box; the two-column bottom section with the feedback form and the department sidebar — is preserved exactly.

```tsx
// src/app/contact/page.tsx
'use client';

import { useState, useEffect } from 'react';
import Image from 'next/image';
import { MapPin, Phone, Mail, ArrowRight, Share2, Users, Camera, Globe, MessageSquare } from 'lucide-react';
import { fetchContactInfo, submitContactForm } from '@/lib/api';
import type { ContactInfo } from '@/types/api';

// Platform → icon map for social links on the contact page
const platformIcon: Record<string, React.ReactNode> = {
  linkedin:  <Users         size={18} />,
  facebook:  <Share2        size={18} />,
  twitter:   <Globe         size={18} />,
  instagram: <Camera        size={18} />,
  whatsapp:  <MessageSquare size={18} />,
};

export default function ContactPage() {
  const [contactInfo, setContactInfo] = useState<ContactInfo | null>(null);

  // ── Form state ──
  const [firstName, setFirstName] = useState('');
  const [email, setEmail]         = useState('');
  const [phone, setPhone]         = useState('');
  const [message, setMessage]     = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [submitResult, setSubmitResult] = useState<{
    ok: boolean;
    message?: string;
    errors?: Record<string, string[]>;
  } | null>(null);

  useEffect(() => {
    fetchContactInfo()
      .then(setContactInfo)
      .catch(() => { /* silently render with fallback values */ });
  }, []);

  // ── Resolved values with fallbacks matching original static content ──
  const bannerLabel  = contactInfo?.banner_label ?? 'Our Office';
  const address      = contactInfo?.address      ?? 'Bottola Bazar, Bhakurta, Savar, Dhaka-1313.';
  const phone_       = contactInfo?.phone        ?? '+8801987644603';
  const emailAddr    = contactInfo?.email        ?? 'contact@greenlandcompliance.com';
  const mapUrl       = contactInfo?.map_embed_url ?? 'https://www.google.com/maps/embed?pb=!1m18!...';
  const officeImgUrl = contactInfo?.office_image_url;
  const socialLinks  = contactInfo?.social_links ?? [];
  const departments  = contactInfo?.departments  ?? [
    { id: 1, title: 'Any Queries',    email: 'contact@greenlandcompliance.com', sort_order: 1 },
    { id: 2, title: 'Help or Support', email: 'help@greenlandcompliance.com',  sort_order: 2 },
    { id: 3, title: 'Job or Career',   email: 'career@greenlandcompliance.com', sort_order: 3 },
  ];

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setSubmitting(true);
    setSubmitResult(null);

    const result = await submitContactForm({
      first_name: firstName,
      email,
      phone: phone || undefined,
      message,
    });

    setSubmitResult(result);
    setSubmitting(false);

    if (result.ok) {
      // Clear the form on success
      setFirstName('');
      setEmail('');
      setPhone('');
      setMessage('');
    }
  }

  return (
    <div style={{ paddingTop: '120px', paddingBottom: '80px' }}>
      <div className="max-w-[1200px] mx-auto px-4">

        {/* ── Top banner — unchanged layout ── */}
        <div style={{ height: '60px' }} className="flex mb-8">
          <div className="px-8 flex items-center" style={{ background: '#333' }}>
            <span className="text-primary font-bold text-sm uppercase tracking-wider">
              {bannerLabel}
            </span>
          </div>
          <div className="flex-1" style={{ background: '#79b940' }} />
        </div>

        {/* ── Top grid: image | map | contact details ── */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">

          {/* Column 1: Office image or placeholder */}
          {officeImgUrl ? (
            <div className="relative h-[350px] rounded-lg overflow-hidden">
              <Image src={officeImgUrl} alt="Our Office" fill className="object-cover" />
            </div>
          ) : (
            // Preserves the original gray dashed placeholder
            <div
              className="h-[350px] border-2 border-dashed border-gray-300 rounded-lg
                         flex items-center justify-center text-gray-400 text-sm"
            >
              Image Placeholder
            </div>
          )}

          {/* Column 2: Google Map — unchanged */}
          <div>
            <iframe
              src={mapUrl}
              width="100%"
              height="350"
              className="rounded-lg border border-gray-200 block"
              loading="lazy"
              allowFullScreen
              referrerPolicy="no-referrer-when-downgrade"
              title="Office Location"
            />
          </div>

          {/* Column 3: Contact details box — unchanged layout */}
          <div
            className="text-white p-10 rounded-lg"
            style={{ background: '#0b132b' }}
          >
            <h3 className="font-bold text-lg mb-6">Contact Details</h3>
            <ul className="space-y-4 text-sm mb-8">
              <li className="flex gap-3 items-start">
                <MapPin size={16} className="text-primary mt-0.5 shrink-0" />
                <span className="text-gray-300">{address}</span>
              </li>
              <li className="flex gap-3 items-center">
                <Phone size={16} className="text-primary shrink-0" />
                <a href={`tel:${phone_.replace(/\s/g, '')}`} className="text-gray-300 hover:text-white">
                  {phone_}
                </a>
              </li>
              <li className="flex gap-3 items-center">
                <Mail size={16} className="text-primary shrink-0" />
                <a href={`mailto:${emailAddr}`} className="text-gray-300 hover:text-white">
                  {emailAddr}
                </a>
              </li>
            </ul>
            {/* Social icons — wired to real links from API */}
            <div className="flex gap-3">
              {socialLinks.length > 0
                ? socialLinks.map((link, idx) => (
                    <a
                      key={idx}
                      href={link.url}
                      target="_blank"
                      rel="noopener noreferrer"
                      aria-label={link.platform}
                      className="w-9 h-9 rounded-full flex items-center justify-center
                                 bg-white/10 hover:bg-primary transition-colors"
                    >
                      {platformIcon[link.platform] ?? <Globe size={18} />}
                    </a>
                  ))
                // Fallback: unchanged static icon divs from original
                : [Share2, Users, Camera, Globe, MessageSquare].map((Icon, i) => (
                    <div
                      key={i}
                      className="w-9 h-9 rounded-full flex items-center justify-center bg-white/10"
                    >
                      <Icon size={18} />
                    </div>
                  ))
              }
            </div>
          </div>
        </div>

        {/* ── Bottom: form (2/3) + info (1/3) ── */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">

          {/* Feedback form — now wired */}
          <div className="lg:col-span-2">
            <h2 className="text-2xl font-bold text-gray-800 mb-6">Feedback form</h2>

            {submitResult?.ok && (
              <div className="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded text-sm">
                {submitResult.message}
              </div>
            )}
            {submitResult && !submitResult.ok && !submitResult.errors && (
              <div className="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded text-sm">
                {submitResult.message}
              </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <input
                    type="text"
                    placeholder="First name *"
                    value={firstName}
                    onChange={(e) => setFirstName(e.target.value)}
                    required
                    className="w-full border border-gray-300 rounded px-4 py-3 text-sm
                               focus:outline-none focus:border-primary"
                  />
                  {submitResult?.errors?.first_name && (
                    <p className="text-xs text-red-500 mt-1">{submitResult.errors.first_name[0]}</p>
                  )}
                </div>
                <div>
                  <input
                    type="email"
                    placeholder="E-mail *"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                    className="w-full border border-gray-300 rounded px-4 py-3 text-sm
                               focus:outline-none focus:border-primary"
                  />
                  {submitResult?.errors?.email && (
                    <p className="text-xs text-red-500 mt-1">{submitResult.errors.email[0]}</p>
                  )}
                </div>
              </div>
              <input
                type="tel"
                placeholder="Phone *"
                value={phone}
                onChange={(e) => setPhone(e.target.value)}
                className="w-full border border-gray-300 rounded px-4 py-3 text-sm
                           focus:outline-none focus:border-primary"
              />
              <div>
                <textarea
                  placeholder="Your Message *"
                  value={message}
                  onChange={(e) => setMessage(e.target.value)}
                  required
                  rows={6}
                  className="w-full border border-gray-300 rounded px-4 py-3 text-sm
                             focus:outline-none focus:border-primary resize-none"
                />
                {submitResult?.errors?.message && (
                  <p className="text-xs text-red-500 mt-1">{submitResult.errors.message[0]}</p>
                )}
              </div>
              <button
                type="submit"
                disabled={submitting}
                className="inline-flex items-center gap-2 bg-primary hover:bg-green-600
                           text-white font-semibold px-8 py-3 rounded transition-colors
                           disabled:opacity-60 disabled:cursor-not-allowed"
              >
                {submitting ? 'Sending…' : 'Submit'} <ArrowRight size={16} />
              </button>
            </form>
          </div>

          {/* Department contact info — from API */}
          <div>
            <h3 className="text-lg font-bold text-gray-800 mb-6">Your contact</h3>
            <ul className="space-y-6">
              {departments.map((dept) => (
                <li key={dept.id}>
                  <p className="font-semibold text-gray-800 mb-1">{dept.title}</p>
                  <a
                    href={`mailto:${dept.email}`}
                    className="text-primary text-sm hover:underline"
                  >
                    {dept.email}
                  </a>
                </li>
              ))}
            </ul>
          </div>
        </div>

      </div>
    </div>
  );
}
```

---

## 16. `src/app/resources/page.tsx`

The resources page is already `'use client'` due to the `activeTab` state. After migration, all three resource types are fetched in parallel on mount. The active tab determines which data set is rendered.

The CSS module (`resources.module.css`) is **not touched**. The format badge colour logic that was previously in a helper function now reads the format string directly from the API response.

```tsx
// src/app/resources/page.tsx
'use client';

import { useState, useEffect } from 'react';
import { FileText, Download, ExternalLink } from 'lucide-react';
import { fetchPublications, fetchFormTemplates, fetchNews } from '@/lib/api';
import type { Publication, FormTemplate, NewsPost } from '@/types/api';
import styles from './resources.module.css';

type ResourceTab = 'publications' | 'forms' | 'news';

// ─────────────────────────────────────────────────────────────────────────────
// Format badge colour helper — unchanged from static version
// ─────────────────────────────────────────────────────────────────────────────

const formatColors: Record<string, string> = {
  pdf:   '#e74c3c',
  word:  '#2b579a',
  excel: '#217346',
  jpg:   '#f39c12',
  png:   '#f39c12',   // Added PNG support (missing in static version)
};

function FormatBadge({ format }: { format: string }) {
  return (
    <span
      className={styles.formatBadge}
      style={{ background: formatColors[format] ?? '#888' }}
    >
      {format.toUpperCase()}
    </span>
  );
}

// ─────────────────────────────────────────────────────────────────────────────
// Date formatter — converts "2026-05-10" to "May 10, 2026"
// ─────────────────────────────────────────────────────────────────────────────

function formatDate(iso: string): string {
  const d = new Date(iso + 'T00:00:00');   // Prevent timezone shift
  return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

export default function ResourcesPage() {
  const [activeTab, setActiveTab] = useState<ResourceTab>('publications');
  const [publications, setPublications] = useState<Publication[]>([]);
  const [forms, setForms] = useState<Record<string, FormTemplate[]>>({});
  const [news, setNews] = useState<NewsPost[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Fetch all three in parallel on mount — tab switching uses cached state
    Promise.all([
      fetchPublications(),
      fetchFormTemplates(),
      fetchNews(),
    ])
      .then(([pubs, formData, newsData]) => {
        setPublications(pubs);
        setForms(formData);
        setNews(newsData);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, []);

  const tabs: { id: ResourceTab; label: string }[] = [
    { id: 'publications', label: 'Publications' },
    { id: 'forms',        label: 'Forms & Templates' },
    { id: 'news',         label: 'News' },
  ];

  return (
    <div className={styles.container}>

      {/* ── Page heading and tabs — unchanged layout ── */}
      <h1 className={styles.title}>Resources</h1>

      <div className={styles.tabs}>
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => setActiveTab(tab.id)}
            className={`${styles.tab} ${activeTab === tab.id ? styles.active : ''}`}
          >
            {tab.label}
          </button>
        ))}
      </div>

      {loading && (
        <div className="text-center py-20 text-gray-400">Loading resources…</div>
      )}

      {/* ── Publications tab ── */}
      {!loading && activeTab === 'publications' && (
        <div className={styles.pubGrid}>
          {publications.map((pub) => (
            <div key={pub.id} className={styles.pubCard}>
              <FormatBadge format={pub.format} />
              <FileText size={32} className="text-gray-400 mb-3" />
              <p className={styles.pubCategory}>{pub.category}</p>
              <h4 className={styles.pubTitle}>{pub.title}</h4>
              <a
                href={pub.file_url ?? undefined}
                target={pub.file_url ? '_blank' : undefined}
                rel="noopener noreferrer"
                className={styles.downloadLink}
              >
                <Download size={14} /> Download Resource
              </a>
            </div>
          ))}
        </div>
      )}

      {/* ── Forms & Templates tab ── */}
      {!loading && activeTab === 'forms' && (
        <div>
          {Object.entries(forms).map(([group, items]) => (
            <div key={group} className={styles.categorySection}>
              <h3 className={styles.categoryTitle}>{group}</h3>
              <div className={styles.pubGrid}>
                {items.map((item) => (
                  <div key={item.id} className={styles.pubCard}>
                    <FormatBadge format={item.format} />
                    <p className={styles.pubCategory}>{item.language}</p>
                    <h4 className={styles.pubTitle}>{item.title}</h4>
                    <a
                      href={item.file_url ?? undefined}
                      target={item.file_url ? '_blank' : undefined}
                      rel="noopener noreferrer"
                      className={styles.downloadLink}
                    >
                      <Download size={14} /> Get Template
                    </a>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>
      )}

      {/* ── News tab ── */}
      {!loading && activeTab === 'news' && (
        <div className={styles.newsGrid}>
          {news.map((post) => (
            <div key={post.id} className={styles.newsCard}>
              {/* Image or format placeholder — same logic as static version */}
              <div className={styles.newsImage}>
                {post.image_url
                  ? <img src={post.image_url} alt={post.title} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                  : (
                    <span>
                      {post.format.toUpperCase()} Image
                    </span>
                  )
                }
              </div>
              <div className={styles.newsInfo}>
                <div className={styles.newsMeta}>
                  <span>{post.category}</span>
                  <span>{formatDate(post.published_at)}</span>
                </div>
                <h4 className={styles.pubTitle}>{post.title}</h4>
                <a
                  href={post.external_url ?? undefined}
                  target={post.external_url ? '_blank' : undefined}
                  rel="noopener noreferrer"
                  className={styles.downloadLink}
                >
                  Read More <ExternalLink size={13} />
                </a>
              </div>
            </div>
          ))}
        </div>
      )}

    </div>
  );
}
```

---

## 17. New Pages: Privacy, Terms, Cookies

These three routes were previously missing (links pointed to non-existent routes). The CMS page endpoint (`GET /pages/{slug}`) returns the title and full HTML content for each. Each page is a small async Server Component.

Because the HTML comes from the admin panel (trusted source), `dangerouslySetInnerHTML` is appropriate here. If untrusted user content were involved, DOMPurify would be required, but admin-entered content is considered trusted.

```tsx
// src/app/privacy/page.tsx
import { fetchCmsPage } from '@/lib/api';
import type { Metadata } from 'next';

export async function generateMetadata(): Promise<Metadata> {
  try {
    const page = await fetchCmsPage('privacy');
    return { title: `${page.title} — Greenland Business & Compliance` };
  } catch {
    return { title: 'Privacy Policy — Greenland Business & Compliance' };
  }
}

export default async function PrivacyPage() {
  let page = null;
  try {
    page = await fetchCmsPage('privacy');
  } catch {
    // Page not yet created in admin panel
  }

  return (
    <div style={{ paddingTop: '120px', paddingBottom: '80px' }}>
      <div className="max-w-[900px] mx-auto px-4">
        <h1 className="text-3xl font-bold text-gray-800 mb-8">
          {page?.title ?? 'Privacy Policy'}
        </h1>
        {page?.content ? (
          <div
            className="prose prose-sm max-w-none text-gray-600"
            dangerouslySetInnerHTML={{ __html: page.content }}
          />
        ) : (
          <p className="text-gray-400">
            This page is being prepared. Please check back soon.
          </p>
        )}
      </div>
    </div>
  );
}
```

Create identical files for `src/app/terms/page.tsx` and `src/app/cookies/page.tsx`, replacing the slug `'privacy'` with `'terms'` and `'cookies'` respectively, and updating the fallback title text.

---

## 18. Removing Prisma Files

The Prisma files present in the frontend have no effect on any rendered page (confirmed by the static inventory). They can be removed now that the data layer is the Laravel REST API.

Delete these files:

- `src/lib/prisma.ts`
- `prisma.config.ts`
- `prisma/schema.prisma`
- `prisma/seed.ts`

Also remove Prisma-related package entries from `package.json` and regenerate `package-lock.json`. In the current frontend this means removing `@prisma/client` from `dependencies`, removing `prisma` from `devDependencies`, removing the root `"prisma": { "seed": "ts-node prisma/seed.ts" }` block, and removing `ts-node` if it is only present for the Prisma seed script. Run `npm uninstall @prisma/client prisma ts-node` if `ts-node` has no other project use. The build will succeed without them.

---

## 19. ISR And Caching Strategy Summary

The caching decisions in this document follow the guidance in `API.md` section 6 exactly. The table below is a quick reference.

| API endpoint | Fetch location | `revalidate` |
| --- | --- | --- |
| `GET /site` | `layout.tsx` (server) | 300 s |
| `GET /navigation` | `layout.tsx` (server) | 300 s |
| `GET /hero` | `page.tsx` (home, server) | 60 s |
| `GET /services` | `services/page.tsx` (client useEffect) | 300 s |
| `GET /case-studies` | `case-studies/page.tsx` (client useEffect) | 300 s |
| `GET /testimonials?page=case_studies` | `case-studies/page.tsx` (client useEffect) | 300 s |
| `GET /about` | `about/page.tsx` (client useEffect) | 300 s |
| `GET /contact` | `contact/page.tsx` (client useEffect) | no-store |
| `GET /resources/publications` | `resources/page.tsx` (client useEffect) | 300 s |
| `GET /resources/forms` | `resources/page.tsx` (client useEffect) | 300 s |
| `GET /resources/news` | `resources/page.tsx` (client useEffect) | 300 s |
| `GET /pages/{slug}` | `privacy`, `terms`, `cookies` (server) | 300 s |
| `POST /contact` | `contact/page.tsx` (client event handler) | no-store |

**Important caveat on Client Component caching:** The `next: { revalidate }` option on `fetch` applies only when `fetch` is called in Server Components. Client Components that call `fetch` inside `useEffect` do not participate in Next.js ISR. For client-fetched data, the browser's own HTTP caching applies (controlled by `Cache-Control` headers from the Laravel API). For this project, the admin panel content changes infrequently, so browser-level caching or a simple SWR-style re-fetch on page focus is sufficient. A future upgrade could extract the server-fetched data into a route segment using a thin Server Component wrapper that passes the pre-fetched data as props to the Client Component — but that is not required to complete this migration.

---

## 20. Transition Period: Local Public Assets

During the transition period — after the frontend is updated but before the backend team has migrated all images to Laravel storage — the local `public/` assets remain in place and serve as the fallback for null API `image_url` values. The fallback logic in every component checks for `null` before using a remote URL and falls back to the local path.

Once the backend team has:
1. Copied all images to `backend/storage/app/public/` (per `BACKEND.md` section 12).
2. Run `php artisan storage:link`.
3. Updated the database seeders to populate the correct storage paths.
4. Confirmed that all `image_url` fields return non-null values.

At that point, the local `public/Background Img/`, `public/about/`, and `public/logo/` folders may be deleted from the frontend. The only local asset that stays is `src/app/favicon.ico`.

---

## 21. Data Quality Corrections Applied In This Migration

The following issues from the static inventory are corrected as part of this migration. They are corrected in the backend seeders (see `BACKEND.md` section 15), so the API returns clean data. The frontend simply renders what the API returns; no special frontend logic is needed to handle these.

The mojibake in the copyright text is corrected — the API returns a clean UTF-8 string. The FAQ open indicator mojibake is replaced with the standard en-dash `–` character in the frontend JSX (not relying on the API). The mission bullet dash mojibake is cleaned in the seeder — the API returns `—`. The `Contact US` nav label capitalisation is preserved in the seeder as-is (the admin can fix it through the panel). The `Any Quires` department name is seeded as `Any Queries`. The `Govt. Gaget` category is seeded as `Govt. Gazette`. Service prices are seeded as USD strings matching the current frontend; the admin should update them to BDT pricing.

---

## 22. Static Actions And No-Op Controls

The dynamic migration must make these currently static controls functional without changing their visual structure: footer social icons, footer CTA, About hero CTA, About sidebar company presentation, About footer CTA, Case Studies company presentation, contact social icons, contact form submit, resource publication downloads, resource form/template downloads, resource news read-more links, and the missing `/privacy`, `/terms`, and `/cookies` pages.

The Navbar search icon is intentionally visual-only in this migration because there is no current search UI, search results route, or search endpoint in `API.md`. Team member `view profile` buttons remain visual-only unless a separate team profile route and API endpoint are added; the dynamic migration should not invent that route.

---

## 23. Admin CRUD Compatibility

The Next.js frontend does not call Laravel admin CRUD routes directly. Admin CRUD changes are supported through the public API endpoints consumed in this document:

| Admin-managed content | Frontend support |
| --- | --- |
| Site settings, social links, footer CTA, nav items | `layout.tsx`, `Navbar`, `Footer`, and Contact page API data |
| Hero settings and slides | Home page `Hero` component via `GET /hero` |
| Service categories and services | Services page tabs/cards via `GET /services` |
| Case studies and categories | Case Studies page via `GET /case-studies`; category endpoint is reserved for future filters |
| About settings, timeline, mission, approach, achievements, partners, team, FAQs, testimonials | About page via bundled `GET /about` payload |
| Contact departments and contact messages | Contact page via `GET /contact` and `POST /contact` |
| Publications, forms/templates, news posts | Resources page tabs via `GET /resources/*` |
| CMS pages | `/privacy`, `/terms`, `/cookies` via `GET /pages/{slug}` |

This means backend CRUD is frontend-compatible when every admin save changes the corresponding public API response without requiring frontend code edits.

---

## 24. Component Prop Interface Summary

This table lists every component that gains new props, for quick reference during implementation.

| Component | New Props | Type |
| --- | --- | --- |
| `Navbar` | `site` | `SiteSettings \| null` |
| `Navbar` | `navItems` | `NavItem[]` |
| `Footer` | `site` | `SiteSettings \| null` |
| `Footer` | `nav` | `Navigation \| null` |
| `Hero` | `data` | `HeroData \| null` |

All page components (`services/page.tsx`, `about/page.tsx`, etc.) receive no props from their parent — they fetch their own data internally via `useEffect` or as async Server Components.

---

## 25. Implementation Checklist

Work through these steps in order. Each step is independently testable.

**Step 0:** Before editing any Next.js source file, read the relevant local Next.js documentation under `frontend/node_modules/next/dist/docs/`, as required by `AGENTS.md`.

**Step 1:** Add `.env.local` with `NEXT_PUBLIC_API_URL` and `NEXT_PUBLIC_SITE_URL`. Confirm both variables are readable in a Server Component by temporarily logging them in `layout.tsx`.

**Step 2:** Update `next.config.ts` with the `remotePatterns` block. Run `npm run build` — it should succeed.

**Step 3:** Create `src/types/api.ts` with all interfaces. No runtime effect; confirms TypeScript compiles correctly.

**Step 4:** Create `src/lib/api.ts`. Import one function in a test file and confirm TypeScript is happy.

**Step 5:** Update `layout.tsx`. Confirm the Navbar and Footer still render (with fallback values if the backend is not yet running).

**Step 6:** Update `Navbar.tsx`. Verify the mobile menu, active link highlighting, and top bar information all work.

**Step 7:** Update `Footer.tsx`. Verify all four columns render, social links are clickable, and the copyright text is correct.

**Step 8:** Update `src/components/Hero.tsx` and `src/app/page.tsx`. Verify the slider advances automatically and the headline/CTAs come from the API.

**Step 9:** Update `services/page.tsx`. Verify all tabs appear, switching tabs changes the service cards, and badges render correctly.

**Step 10:** Update `case-studies/page.tsx`. Verify the grid renders, images show or the placeholder shows, and the sidebar testimonials render.

**Step 11:** Update `about/page.tsx`. Verify all six sidebar tabs switch content correctly, the accordion opens and closes, and images render or show placeholders.

**Step 12:** Update `contact/page.tsx`. Verify the map loads, contact details are correct, the form submits successfully, and field validation errors display.

**Step 13:** Update `resources/page.tsx`. Verify all three tabs show content, format badges have the correct colours, and download links are either real URLs or an intentionally disabled/unavailable visual state. Do not leave permanent `href="#"` controls for API-backed downloads.

**Step 14:** Create `privacy/page.tsx`, `terms/page.tsx`, and `cookies/page.tsx`. Verify the routes exist and render either the CMS content or the "being prepared" fallback.

**Step 15:** Delete `src/lib/prisma.ts`, `prisma.config.ts`, `prisma/schema.prisma`, and `prisma/seed.ts`. Remove `@prisma/client`, `prisma`, the root Prisma seed block, and `ts-node` if it is only used for the Prisma seed script from `package.json`; regenerate `package-lock.json`. Run `npm run build` to confirm no imports were missed.

**Step 16:** Run `npm run lint` and resolve any TypeScript or ESLint warnings.

**Step 17:** Deploy to staging. Smoke-test every route. Confirm all images load from the API storage URL. Confirm the contact form submits and appears in the admin panel inbox.

---

## 26. File Coverage Checklist

Files that must be created or modified to complete this migration:

Environment coverage: `frontend/.env.local` must include both `NEXT_PUBLIC_API_URL` and `NEXT_PUBLIC_SITE_URL`.

- `frontend/.env.local` — add with `NEXT_PUBLIC_API_URL` and `NEXT_PUBLIC_SITE_URL`
- `frontend/next.config.ts` — modify
- `frontend/src/types/api.ts` — add
- `frontend/src/lib/api.ts` — add
- `frontend/src/app/layout.tsx` — modify
- `frontend/src/components/Navbar.tsx` — modify
- `frontend/src/components/Footer.tsx` — modify
- `frontend/src/components/Hero.tsx` — modify
- `frontend/src/app/page.tsx` — modify
- `frontend/src/app/services/page.tsx` — modify
- `frontend/src/app/services/services.module.css` — not touched
- `frontend/src/app/case-studies/page.tsx` — modify
- `frontend/src/app/case-studies/case-studies.module.css` — not touched
- `frontend/src/app/about/page.tsx` — modify
- `frontend/src/app/contact/page.tsx` — modify
- `frontend/src/app/resources/page.tsx` — modify
- `frontend/src/app/resources/resources.module.css` — not touched
- `frontend/src/app/globals.css` — not touched
- `frontend/src/app/page.module.css` — not touched (unused, leave in place)
- `frontend/src/app/privacy/page.tsx` — add
- `frontend/src/app/terms/page.tsx` — add
- `frontend/src/app/cookies/page.tsx` — add
- `frontend/src/lib/prisma.ts` — delete
- `frontend/prisma.config.ts` — delete
- `frontend/prisma/schema.prisma` — delete
- `frontend/prisma/seed.ts` — delete
- `frontend/package.json` — remove unused Prisma dependencies, dev dependencies, and seed config
- `frontend/package-lock.json` — regenerate after Prisma package removal
