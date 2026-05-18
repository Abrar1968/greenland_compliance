import { expect, test, type APIRequestContext, type Page } from '@playwright/test';

const apiBase = process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000/api/v1';

type ApiEnvelope<T> = {
  data: T;
};

async function apiData<T>(request: APIRequestContext, path: string): Promise<T> {
  const response = await request.get(`${apiBase}${path}`);
  expect(response.ok(), `${path} should return a successful response`).toBeTruthy();

  const json = await response.json() as ApiEnvelope<T>;
  expect(json).toHaveProperty('data');

  return json.data;
}

async function gotoAndCheck(page: Page, path: string): Promise<void> {
  const imageFailures: string[] = [];
  page.on('response', (response) => {
    if (response.url().includes('/_next/image') && response.status() >= 400) {
      imageFailures.push(`${response.status()} ${response.url()}`);
    }
  });

  await page.goto(path, { waitUntil: 'domcontentloaded' });
  await expect(page.locator('body')).toBeVisible();
  await expect(page.locator('body')).not.toContainText('Application error');
  await expect(page.locator('body')).not.toContainText('Unhandled Runtime Error');
  expect(imageFailures, `broken optimized images on ${path}`).toEqual([]);
}

async function expectBodyToContain(page: Page, text: string): Promise<void> {
  await expect(page.locator('body')).toContainText(text, { timeout: 60_000 });
}

function plainText(html: string): string {
  return html.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}

test('home page renders API-backed hero and navigation', async ({ page, request }) => {
  const [hero, navigation] = await Promise.all([
    apiData<{
      headline_line1: string;
      headline_line2: string;
      slides: Array<{ image_url: string | null }>;
    }>(request, '/hero'),
    apiData<{ header: Array<{ label: string }> }>(request, '/navigation'),
  ]);

  await gotoAndCheck(page, '/');

  await expectBodyToContain(page, hero.headline_line1);
  await expectBodyToContain(page, hero.headline_line2);
  await expect(page.getByRole('link', { name: navigation.header[0].label }).first()).toBeVisible();
  expect(hero.slides.length).toBeGreaterThan(0);
});

test('services page renders API service categories and services', async ({ page, request }) => {
  const categories = await apiData<Array<{
    label: string;
    services: Array<{ title: string; description: string | null }>;
  }>>(request, '/services');

  await gotoAndCheck(page, '/services');

  await expect(page.getByRole('button', { name: categories[0].label }).first()).toBeVisible({ timeout: 60_000 });
  await expectBodyToContain(page, categories[0].services[0].title);
});

test('case studies page renders API case studies and presentation state', async ({ page, request }) => {
  const caseStudies = await apiData<Array<{ title: string; summary: string | null }>>(request, '/case-studies');
  const site = await apiData<{ company_presentation_url: string | null }>(request, '/site');

  await gotoAndCheck(page, '/case-studies');

  await expectBodyToContain(page, caseStudies[0].title);
  await expectBodyToContain(page, 'Company presentation');

  if (site.company_presentation_url) {
    await expect(page.getByRole('link', { name: /company presentation/i }).first()).toHaveAttribute('href', site.company_presentation_url);
  } else {
    await expect(page.locator('[aria-disabled="true"]').filter({ hasText: 'Company Presentation' }).first()).toBeVisible();
  }
});

test('about page renders API-backed overview content', async ({ page, request }) => {
  const about = await apiData<{
    banner_label: string;
    hero: { heading_line1: string; heading_line2: string };
    overview: { mission_heading: string; mission_bullets: Array<{ text: string }> };
  }>(request, '/about');

  await gotoAndCheck(page, '/about');

  await expectBodyToContain(page, about.banner_label);
  await expectBodyToContain(page, about.hero.heading_line1);
  await expectBodyToContain(page, about.hero.heading_line2);
  await expectBodyToContain(page, about.overview.mission_heading);
  await expectBodyToContain(page, about.overview.mission_bullets[0].text);
});

test('contact page renders API contact details and submits validation through backend', async ({ page, request }) => {
  const contact = await apiData<{
    banner_label: string;
    address: string;
    phone: string;
    email: string;
  }>(request, '/contact');

  await gotoAndCheck(page, '/contact');

  await expectBodyToContain(page, contact.banner_label);
  await expectBodyToContain(page, contact.address);
  await expectBodyToContain(page, contact.phone);
  await expectBodyToContain(page, contact.email);

  const validation = await request.post(`${apiBase}/contact`, { data: {} });
  expect(validation.status()).toBe(422);
  const validationJson = await validation.json();
  expect(validationJson.error).toBe('Validation failed');
  expect(validationJson.messages).toBeTruthy();
});

test('resources page renders API publications, forms, and news tabs', async ({ page, request }) => {
  const [publications, forms, news] = await Promise.all([
    apiData<Array<{ title: string }>>(request, '/resources/publications'),
    apiData<Record<string, Array<{ title: string }>>>(request, '/resources/forms'),
    apiData<Array<{ title: string }>>(request, '/resources/news'),
  ]);

  const firstFormGroup = Object.values(forms).find((items) => items.length > 0);
  expect(firstFormGroup).toBeTruthy();

  await gotoAndCheck(page, '/resources');

  await expectBodyToContain(page, publications[0].title);

  await page.getByRole('button', { name: /forms/i }).first().click();
  await expectBodyToContain(page, firstFormGroup![0].title);

  await page.getByRole('button', { name: /news/i }).first().click();
  await expectBodyToContain(page, news[0].title);
});

for (const slug of ['privacy', 'terms', 'cookies']) {
  test(`${slug} CMS page renders API title and content`, async ({ page, request }) => {
    const cmsPage = await apiData<{ title: string; content: string }>(request, `/pages/${slug}`);

    await gotoAndCheck(page, `/${slug}`);

    await expectBodyToContain(page, cmsPage.title);
    await expectBodyToContain(page, plainText(cmsPage.content).slice(0, 40));
  });
}
