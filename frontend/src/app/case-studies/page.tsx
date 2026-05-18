'use client';

import { type ReactNode, useEffect, useState } from 'react';
import Image from 'next/image';
import Link from 'next/link';
import { Play, Quote } from 'lucide-react';
import { fetchCaseStudies, fetchSiteSettings, fetchTestimonials } from '@/lib/api';
import type { CaseStudy, SiteSettings, Testimonial as TestimonialType } from '@/types/api';
import styles from './case-studies.module.css';

export default function CaseStudiesPage() {
  const [caseStudies, setCaseStudies] = useState<CaseStudy[]>([]);
  const [testimonials, setTestimonials] = useState<TestimonialType[]>([]);
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
      })
      .catch(() => {
        setCaseStudies([]);
        setTestimonials([]);
      })
      .finally(() => setLoading(false));
  }, []);

  return (
    <main className={styles.container}>
      <div className={styles.layout}>
        <div className={styles.mainContent}>
          {loading && (
            <div className="text-center py-20 text-gray-400">Loading case studies...</div>
          )}

          {!loading && caseStudies.length === 0 && (
            <div className="text-center py-20 text-gray-400">No case studies are available yet.</div>
          )}

          <div className={styles.grid}>
            {caseStudies.map((study) => (
              <Link key={study.id} href={`/case-studies/${study.slug}`} className={styles.caseItem}>
                <div className={styles.imageWrapper}>
                  {study.image_url ? (
                    <Image
                      src={study.image_url}
                      alt={study.title}
                      fill
                      sizes="(max-width: 768px) 100vw, 33vw"
                      className="object-cover"
                    />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold italic">
                      img here
                    </div>
                  )}
                </div>
                <span className={styles.category}>{study.category.name}</span>
                <h3 className={styles.title}>{study.title}</h3>
              </Link>
            ))}
          </div>
        </div>

        <aside className={styles.sidebar}>
          <div className="space-y-8">
            <ConditionalDownloadBox href={site?.company_presentation_url}>
              <div className={styles.iconCircle}>
                <Play size={20} className="fill-white text-white ml-1" />
              </div>
              <div>
                <span className={styles.downloadLabel}>Download</span>
                <div className={styles.downloadTitle}>Company presentation</div>
              </div>
            </ConditionalDownloadBox>

            <div className={styles.helpBox}>
              <h4 className={styles.helpTitle}>How can we help you?</h4>
              <p className={styles.helpDesc}>
                Contact us for your query or submit a business inquiry online.
              </p>
              <Link href="/contact" className={styles.contactBtn}>
                CONTACT US
              </Link>
            </div>

            <div className="space-y-6">
              {testimonials.map((testimonial) => (
                <Testimonial key={testimonial.id} testimonial={testimonial} />
              ))}
            </div>
          </div>
        </aside>
      </div>
    </main>
  );
}

function ConditionalDownloadBox({ href, children }: { href?: string | null; children: ReactNode }) {
  if (href) {
    return (
      <a href={href} target="_blank" rel="noopener noreferrer" className={styles.downloadBox}>
        {children}
      </a>
    );
  }

  return (
    <div className={`${styles.downloadBox} opacity-70 cursor-not-allowed`} aria-disabled="true">
      {children}
    </div>
  );
}

function Testimonial({ testimonial }: { testimonial: TestimonialType }) {
  return (
    <div className={styles.testimonial}>
      <p className={styles.quoteText}>&quot;{testimonial.quote}&quot;</p>
      <div className={styles.quoteIcon}>
        <Quote size={48} fill="currentColor" />
      </div>
      <div className={styles.authorRow}>
        <div className={styles.avatar}>
          {testimonial.avatar_url ? (
            <Image
              src={testimonial.avatar_url}
              alt={testimonial.author}
              fill
              sizes="48px"
              className="object-cover"
            />
          ) : (
            <div className="w-full h-full bg-gray-200" />
          )}
        </div>
        <div>
          <div className={styles.authorName}>{testimonial.author}</div>
          <div className={styles.authorRole}>{testimonial.role}</div>
        </div>
      </div>
    </div>
  );
}
