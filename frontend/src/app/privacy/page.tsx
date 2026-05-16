import type { Metadata } from 'next';
import { safeApiFetch } from '@/lib/api';
import type { CmsPage } from '@/types/api';

export async function generateMetadata(): Promise<Metadata> {
  const page = await safeApiFetch<CmsPage>('/pages/privacy', { revalidate: 300 });
  return { title: `${page?.title ?? 'Privacy Policy'} - Greenland Business & Compliance` };
}

export default async function PrivacyPage() {
  const page = await safeApiFetch<CmsPage>('/pages/privacy', { revalidate: 300 });

  return (
    <main className="pt-[140px] pb-20 bg-white font-sans">
      <div className="max-w-[900px] mx-auto px-6">
        <h1 className="text-3xl font-bold text-gray-800 mb-8">
          {page?.title ?? 'Privacy Policy'}
        </h1>
        {page?.content ? (
          <div className="prose prose-sm max-w-none text-gray-600" dangerouslySetInnerHTML={{ __html: page.content }} />
        ) : (
          <p className="text-gray-400">This page is being prepared. Please check back soon.</p>
        )}
      </div>
    </main>
  );
}
