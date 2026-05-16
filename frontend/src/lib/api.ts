import type {
  AboutData,
  ApiCollection,
  ApiGrouped,
  ApiSingle,
  CaseStudy,
  CmsPage,
  ContactInfo,
  ContactPayload,
  FormTemplate,
  HeroData,
  Navigation,
  NewsPost,
  Publication,
  ServiceCategory,
  SiteSettings,
  Testimonial,
  ValidationError,
} from "@/types/api";

const API_BASE = (process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api/v1").replace(/\/$/, "");

type FetchOptions = {
  revalidate?: number;
  cache?: RequestCache;
};

async function apiFetch<T>(path: string, options: FetchOptions = {}): Promise<T> {
  const response = await fetch(`${API_BASE}${path}`, {
    headers: { Accept: "application/json" },
    cache: options.cache,
    next: options.revalidate ? { revalidate: options.revalidate } : undefined,
  });

  if (!response.ok) {
    throw new Error(`API request failed: ${path} (${response.status})`);
  }

  const json = (await response.json()) as ApiSingle<T> | ApiCollection<T> | ApiGrouped<T>;
  return json.data as T;
}

export async function safeApiFetch<T>(path: string, options: FetchOptions = {}): Promise<T | null> {
  try {
    return await apiFetch<T>(path, options);
  } catch {
    return null;
  }
}

export function fetchSiteSettings() {
  return apiFetch<SiteSettings>("/site", { revalidate: 300 });
}

export function fetchNavigation() {
  return apiFetch<Navigation>("/navigation", { revalidate: 300 });
}

export function fetchHero() {
  return apiFetch<HeroData>("/hero", { revalidate: 60 });
}

export function fetchServices() {
  return apiFetch<ServiceCategory[]>("/services", { revalidate: 300 });
}

export function fetchCaseStudies() {
  return apiFetch<CaseStudy[]>("/case-studies", { revalidate: 300 });
}

export function fetchTestimonials(page?: string) {
  return apiFetch<Testimonial[]>(`/testimonials${page ? `?page=${encodeURIComponent(page)}` : ""}`, { revalidate: 300 });
}

export function fetchAbout() {
  return apiFetch<AboutData>("/about", { revalidate: 300 });
}

export function fetchContactInfo() {
  return apiFetch<ContactInfo>("/contact", { cache: "no-store" });
}

export function fetchPublications() {
  return apiFetch<Publication[]>("/resources/publications", { revalidate: 300 });
}

export function fetchFormTemplates() {
  return apiFetch<Record<string, FormTemplate[]>>("/resources/forms", { revalidate: 300 });
}

export function fetchNews() {
  return apiFetch<NewsPost[]>("/resources/news", { revalidate: 300 });
}

export function fetchCmsPage(slug: string) {
  return apiFetch<CmsPage>(`/pages/${encodeURIComponent(slug)}`, { revalidate: 300 });
}

export async function submitContactForm(payload: ContactPayload): Promise<{ ok: true; message: string } | { ok: false; errors: Record<string, string[]> }> {
  const response = await fetch(`${API_BASE}/contact`, {
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify(payload),
  });

  const json = await response.json();

  if (response.ok) {
    return { ok: true, message: json.data.message };
  }

  const error = json as ValidationError;
  return { ok: false, errors: error.messages ?? { form: [error.error ?? "Unable to submit the form."] } };
}

export const submitContact = submitContactForm;

export const apiFallbacks = {
  logo: "/logo/gc.png",
  heroSlides: [
    { id: 1, image_url: "/Background Img/hero1.jpg", alt_text: "Hero slide 1", sort_order: 1 },
    { id: 2, image_url: "/Background Img/hero2..jpg", alt_text: "Hero slide 2", sort_order: 2 },
    { id: 3, image_url: "/Background Img/hero3.jpg", alt_text: "Hero slide 3", sort_order: 3 },
  ],
};
