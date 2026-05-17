import type { Metadata } from 'next';
import { safeApiFetch } from '@/lib/api';
import type { CmsPage } from '@/types/api';

export const dynamic = 'force-dynamic';

export async function generateMetadata(): Promise<Metadata> {
  const page = await safeApiFetch<CmsPage>('/pages/cookies', { cache: 'no-store' });
  return page?.title ? { title: page.title } : {};
}

export default async function CookiesPage() {
  const page = await safeApiFetch<CmsPage>('/pages/cookies', { cache: 'no-store' });

  return (
    <main className="pt-[140px] pb-20 bg-white font-sans">
      <div className="max-w-[900px] mx-auto px-6">
        <h1 className="text-3xl font-bold text-gray-800 mb-8">
          {page?.title ?? ''}
        </h1>
        {page?.content ? (
          <div className="prose prose-sm max-w-none text-gray-600" dangerouslySetInnerHTML={{ __html: page.content }} />
        ) : null}
      </div>
    </main>
  );
}
