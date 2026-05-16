'use client';

import { type ReactNode, useEffect, useState } from 'react';
import Image from 'next/image';
import { FileText, Download, ExternalLink } from 'lucide-react';
import { fetchFormTemplates, fetchNews, fetchPublications } from '@/lib/api';
import type { FormTemplate, NewsPost, Publication } from '@/types/api';
import styles from './resources.module.css';

type ResourceTab = 'publications' | 'forms' | 'news';

const tabs: { id: ResourceTab; label: string }[] = [
  { id: 'publications', label: 'Publications' },
  { id: 'forms', label: 'Forms & Templates' },
  { id: 'news', label: 'News' },
];

function formatClass(format: string) {
  const key = format.toLowerCase();
  if (key in styles) return styles[key];
  return styles.pdf;
}

function formatDate(iso: string) {
  const date = new Date(`${iso}T00:00:00`);
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

export default function ResourcesPage() {
  const [activeTab, setActiveTab] = useState<ResourceTab>('publications');
  const [publications, setPublications] = useState<Publication[]>([]);
  const [forms, setForms] = useState<Record<string, FormTemplate[]>>({});
  const [news, setNews] = useState<NewsPost[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([fetchPublications(), fetchFormTemplates(), fetchNews()])
      .then(([publicationData, formData, newsData]) => {
        setPublications(publicationData);
        setForms(formData);
        setNews(newsData);
      })
      .catch(() => {
        setPublications([]);
        setForms({});
        setNews([]);
      })
      .finally(() => setLoading(false));
  }, []);

  return (
    <main className={styles.container}>
      <h1 className={styles.title}>Resources</h1>

      <div className={styles.tabs}>
        {tabs.map((tab) => (
          <button
            key={tab.id}
            className={`${styles.tab} ${activeTab === tab.id ? styles.active : ''}`}
            onClick={() => setActiveTab(tab.id)}
          >
            {tab.label}
          </button>
        ))}
      </div>

      <div className={styles.content}>
        {loading && (
          <div className="text-center py-20 text-gray-400">Loading resources...</div>
        )}
        {!loading && activeTab === 'publications' && <PublicationsView publications={publications} />}
        {!loading && activeTab === 'forms' && <FormsView forms={forms} />}
        {!loading && activeTab === 'news' && <NewsView news={news} />}
      </div>
    </main>
  );
}

function PublicationsView({ publications }: { publications: Publication[] }) {
  if (publications.length === 0) {
    return <div className="text-center py-20 text-gray-400">No publications are available yet.</div>;
  }

  return (
    <div className={styles.pubGrid}>
      {publications.map((pub) => (
        <div key={pub.id} className={styles.pubCard}>
          <div>
            <div className={styles.pubHeader}>
              <span className={`${styles.formatBadge} ${formatClass(pub.format)}`}>{pub.format}</span>
              <FileText size={20} className="text-gray-300" />
            </div>
            <span className={styles.pubCategory}>{pub.category}</span>
            <h3 className={styles.pubTitle}>{pub.title}</h3>
          </div>
          <ResourceLink href={pub.file_url} label="Download Resource" icon={<Download size={16} />} />
        </div>
      ))}
    </div>
  );
}

function FormsView({ forms }: { forms: Record<string, FormTemplate[]> }) {
  const entries = Object.entries(forms);

  if (entries.length === 0) {
    return <div className="text-center py-20 text-gray-400">No forms are available yet.</div>;
  }

  return (
    <div className="space-y-12">
      {entries.map(([group, items]) => (
        <section key={group} className={styles.categorySection}>
          <h2 className={styles.categoryTitle}>{group}</h2>
          <div className={styles.pubGrid}>
            {items.map((item) => (
              <div key={item.id} className={styles.pubCard}>
                <div>
                  <div className={styles.pubHeader}>
                    <span className={`${styles.formatBadge} ${formatClass(item.format)}`}>{item.format}</span>
                    <span className="text-xs font-bold text-gray-400 uppercase">{item.language}</span>
                  </div>
                  <h3 className={styles.pubTitle}>{item.title}</h3>
                </div>
                <ResourceLink href={item.file_url} label="Get Template" icon={<Download size={16} />} />
              </div>
            ))}
          </div>
        </section>
      ))}
    </div>
  );
}

function NewsView({ news }: { news: NewsPost[] }) {
  if (news.length === 0) {
    return <div className="text-center py-20 text-gray-400">No news is available yet.</div>;
  }

  return (
    <div className={styles.newsGrid}>
      {news.map((item) => (
        <div key={item.id} className={styles.newsCard}>
          <div className={styles.newsImage}>
            {item.image_url ? (
              <Image
                src={item.image_url}
                alt={item.title}
                fill
                sizes="(max-width: 768px) 100vw, 33vw"
                className="object-cover"
              />
            ) : (
              <span>{item.format.toUpperCase()} Image</span>
            )}
          </div>
          <div className={styles.newsInfo}>
            <div className={styles.newsMeta}>
              <span>{item.category}</span>
              <span>{formatDate(item.published_at)}</span>
            </div>
            <h3 className={styles.pubTitle}>{item.title}</h3>
            <ResourceLink href={item.external_url} label="Read More" icon={<ExternalLink size={16} />} iconAfter />
          </div>
        </div>
      ))}
    </div>
  );
}

function ResourceLink({ href, label, icon, iconAfter = false }: { href: string | null; label: string; icon: ReactNode; iconAfter?: boolean }) {
  const content = iconAfter ? <>{label} {icon}</> : <>{icon} {label}</>;

  if (href) {
    return (
      <a href={href} target="_blank" rel="noopener noreferrer" className={styles.downloadLink}>
        {content}
      </a>
    );
  }

  return (
    <span className={`${styles.downloadLink} opacity-60 cursor-not-allowed`} aria-disabled="true">
      {content}
    </span>
  );
}
