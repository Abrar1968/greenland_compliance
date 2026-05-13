'use client';

import { useState } from 'react';
import { FileText, Download, Book, Newspaper, ExternalLink } from 'lucide-react';
import styles from './resources.module.css';

type ResourceTab = 'publications' | 'forms' | 'news';

export default function ResourcesPage() {
  const [activeTab, setActiveTab] = useState<ResourceTab>('publications');

  return (
    <main className={styles.container}>
      <h1 className={styles.title}>Resources</h1>

      <div className={styles.tabs}>
        <button
          className={`${styles.tab} ${activeTab === 'publications' ? styles.active : ''}`}
          onClick={() => setActiveTab('publications')}
        >
          Publications
        </button>
        <button
          className={`${styles.tab} ${activeTab === 'forms' ? styles.active : ''}`}
          onClick={() => setActiveTab('forms')}
        >
          Forms & Templates
        </button>
        <button
          className={`${styles.tab} ${activeTab === 'news' ? styles.active : ''}`}
          onClick={() => setActiveTab('news')}
        >
          News
        </button>
      </div>

      <div className={styles.content}>
        {activeTab === 'publications' && <PublicationsView />}
        {activeTab === 'forms' && <FormsView />}
        {activeTab === 'news' && <NewsView />}
      </div>
    </main>
  );
}

function PublicationsView() {
  const pubs = [
    { title: 'Government Gazette on Labor Law 2023', format: 'pdf', cat: 'Gazette' },
    { title: 'Company Compliance Timeline 2024', format: 'jpg', cat: 'Timeline' },
    { title: 'Industrial Safety Guidelines', format: 'pdf', cat: 'Nirdeshika' },
    { title: 'Business Ethics & Conduct Book', format: 'pdf', cat: 'Books' },
    { title: 'Environmental Regulations Handbook', format: 'word', cat: 'Manual' },
    { title: 'Taxation Policy Update', format: 'pdf', cat: 'Govt. Gaget' },
  ];

  return (
    <div className={styles.pubGrid}>
      {pubs.map((pub, idx) => (
        <div key={idx} className={styles.pubCard}>
          <div>
            <div className={styles.pubHeader}>
              <span className={`${styles.formatBadge} ${styles[pub.format]}`}>{pub.format}</span>
              <FileText size={20} className="text-gray-300" />
            </div>
            <span className={styles.pubCategory}>{pub.cat}</span>
            <h3 className={styles.pubTitle}>{pub.title}</h3>
          </div>
          <a href="#" className={styles.downloadLink}>
            <Download size={16} /> Download Resource
          </a>
        </div>
      ))}
    </div>
  );
}

function FormsView() {
  const categories = [
    {
      name: 'Human Resources',
      items: [
        { title: 'Employee Onboarding Form', lang: 'English', format: 'word' },
        { title: 'Leave Application Template', lang: 'Bangla', format: 'excel' },
        { title: 'Performance Review Template', lang: 'English', format: 'word' },
      ]
    },
    {
      name: 'Legal & Compliance',
      items: [
        { title: 'Trade License Renewal Form', lang: 'Bangla', format: 'word' },
        { title: 'Compliance Audit Checklist', lang: 'English', format: 'excel' },
        { title: 'Annual Return Template', lang: 'Bangla', format: 'excel' },
      ]
    },
    {
      name: 'Finance & Accounts',
      items: [
        { title: 'Expense Claim Form', lang: 'English', format: 'excel' },
        { title: 'Tax Deduction Statement', lang: 'Bangla', format: 'excel' },
      ]
    }
  ];

  return (
    <div className="space-y-12">
      {categories.map((cat, idx) => (
        <section key={idx} className={styles.categorySection}>
          <h2 className={styles.categoryTitle}>{cat.name}</h2>
          <div className={styles.pubGrid}>
            {cat.items.map((item, i) => (
              <div key={i} className={styles.pubCard}>
                <div>
                  <div className={styles.pubHeader}>
                    <span className={`${styles.formatBadge} ${styles[item.format]}`}>{item.format}</span>
                    <span className="text-xs font-bold text-gray-400 uppercase">{item.lang}</span>
                  </div>
                  <h3 className={styles.pubTitle}>{item.title}</h3>
                </div>
                <a href="#" className={styles.downloadLink}>
                  <Download size={16} /> Get Template
                </a>
              </div>
            ))}
          </div>
        </section>
      ))}
    </div>
  );
}

function NewsView() {
  const news = [
    { title: 'Greenland Compliance joins Global Safety Summit', cat: 'Events', date: 'May 10, 2026', format: 'jpg' },
    { title: 'New Labor Law Amendments: What you need to know', cat: 'Regulatory', date: 'May 08, 2026', format: 'png' },
    { title: 'Annual General Meeting Highlights 2025', cat: 'Corporate', date: 'Apr 25, 2026', format: 'jpg' },
    { title: 'Excellence in Compliance Award Won', cat: 'Awards', date: 'Apr 20, 2026', format: 'png' },
  ];

  return (
    <div className={styles.newsGrid}>
      {news.map((item, idx) => (
        <div key={idx} className={styles.newsCard}>
          <div className={styles.newsImage}>
            <span>{item.format.toUpperCase()} Image</span>
          </div>
          <div className={styles.newsInfo}>
            <div className={styles.newsMeta}>
              <span>{item.cat}</span>
              <span>{item.date}</span>
            </div>
            <h3 className={styles.pubTitle}>{item.title}</h3>
            <a href="#" className={styles.downloadLink}>
              Read More <ExternalLink size={16} />
            </a>
          </div>
        </div>
      ))}
    </div>
  );
}
