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
  return apiFetch<SiteSettings>("/site", { cache: "no-store" });
}

export function fetchNavigation() {
  return apiFetch<Navigation>("/navigation", { cache: "no-store" });
}

export function fetchHero() {
  return apiFetch<HeroData>("/hero", { cache: "no-store" });
}

export function fetchServices() {
  return apiFetch<ServiceCategory[]>("/services", { cache: "no-store" });
}

export function fetchCaseStudies() {
  return apiFetch<CaseStudy[]>("/case-studies", { cache: "no-store" });
}

export function fetchTestimonials(page?: string) {
  return apiFetch<Testimonial[]>(`/testimonials${page ? `?page=${encodeURIComponent(page)}` : ""}`, { cache: "no-store" });
}

export function fetchAbout() {
  return apiFetch<AboutData>("/about", { cache: "no-store" });
}

export function fetchContactInfo() {
  return apiFetch<ContactInfo>("/contact", { cache: "no-store" });
}

export function fetchPublications() {
  return apiFetch<Publication[]>("/resources/publications", { cache: "no-store" });
}

export function fetchFormTemplates() {
  return apiFetch<Record<string, FormTemplate[]>>("/resources/forms", { cache: "no-store" });
}

export function fetchNews() {
  return apiFetch<NewsPost[]>("/resources/news", { cache: "no-store" });
}

export function fetchCmsPage(slug: string) {
  return apiFetch<CmsPage>(`/pages/${encodeURIComponent(slug)}`, { cache: "no-store" });
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
