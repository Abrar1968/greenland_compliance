import Image from 'next/image';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { safeApiFetch } from '@/lib/api';
import type { CaseStudyDetail } from '@/types/api';
import styles from '../case-studies.module.css';

type CaseStudyDetailPageProps = {
  params: Promise<{ slug: string }>;
};

export const dynamic = 'force-dynamic';

export default async function CaseStudyDetailPage({ params }: CaseStudyDetailPageProps) {
  const { slug } = await params;
  const study = await safeApiFetch<CaseStudyDetail>(`/case-studies/${encodeURIComponent(slug)}`, { cache: 'no-store' });

  if (!study) {
    notFound();
  }

  return (
    <main className={styles.container}>
      <Link href="/case-studies" className={styles.backLink}>
        Back to case studies
      </Link>

      <article>
        <header className={styles.detailHeader}>
          <div className={styles.detailImage}>
            {study.image_url ? (
              <Image
                src={study.image_url}
                alt={study.title}
                fill
                sizes="(max-width: 900px) 100vw, 55vw"
                className="object-cover"
                priority
              />
            ) : (
              <div className="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold italic">
                img here
              </div>
            )}
          </div>

          <div>
            <span className={styles.category}>{study.category.name}</span>
            <h1 className={styles.detailTitle}>{study.title}</h1>
            {study.summary ? (
              <p className={styles.detailSummary}>{study.summary}</p>
            ) : null}
          </div>
        </header>

        <div
          className={styles.detailBody}
          dangerouslySetInnerHTML={{ __html: study.body || '<p>No case study description is available yet.</p>' }}
        />
      </article>
    </main>
  );
}
