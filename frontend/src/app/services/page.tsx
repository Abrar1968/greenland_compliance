'use client';

import { useState } from 'react';
import styles from './services.module.css';

const TABS = [
  'advisory',
  'audit',
  'consulting',
  'human capital',
  'mergers & acquisitions',
  'operations',
  'regulatory',
  'strategy',
  'tax'
];

interface ServiceItem {
  title: string;
  price: string;
  description: string;
  badge?: 'NEW' | 'SPECIAL';
}

const SERVICES_DATA: Record<string, ServiceItem[]> = {
  advisory: [
    {
      title: 'Financial Services',
      price: '$75',
      description: "Companies dislike the term 'turnaround consulting' because it represents failure. The truth is that turnaround consulting represents success.",
      badge: 'NEW'
    },
    {
      title: 'Strategic planning',
      price: '$60',
      description: 'Bonds and commodities are much more stable than stocks and trades. We allow our clients to invest in the right bonds & commodities.'
    },
    {
      title: 'Audit & Assurance',
      price: '$115',
      description: 'Audit and assurance is all about meticulous data analysis. Everything needs to be checked, double checked, and triple checked.',
      badge: 'SPECIAL'
    },
    {
      title: 'Trades & Stocks',
      price: '$57',
      description: 'This allows us to specialize in all dimensions of trades and stocks, because we have a specialist within the team for every scenario.'
    },
    {
      title: 'Strategic Planning',
      price: '$35',
      description: 'We work with our clients and do a deep analysis of their business. We help prepare possible outcomes to different decisions.',
      badge: 'NEW'
    },
    {
      title: 'Financial Projections',
      price: '$80',
      description: 'This stops companies from taking drastic measures like downsizing or closing down sites; those things happen only with no.'
    },
    {
      title: 'International Business Opportunities',
      price: '$58',
      description: 'We allow you to enter international waters without having to worry about making a mistake, as experience.'
    },
    {
      title: 'Business Planning, Strategy & Execution',
      price: '$49',
      description: 'Execution is the single most important part of the whole process, poor execution can result in a lot of lost time and money.',
      badge: 'NEW'
    }
  ],
  audit: [
    {
      title: 'External Audit',
      price: '$200',
      description: 'Comprehensive external auditing services for large corporations and SMEs to ensure regulatory compliance.',
      badge: 'NEW'
    },
    {
      title: 'Internal Control Review',
      price: '$150',
      description: 'Evaluation and improvement of your internal control systems to mitigate risks and enhance operational efficiency.'
    }
  ]
  // Add other tabs as needed, for now I'll just reuse or provide defaults
};

export default function ServicesPage() {
  const [activeTab, setActiveTab] = useState('advisory');

  const services = SERVICES_DATA[activeTab] || SERVICES_DATA['advisory'];

  return (
    <main className={styles.container}>
      <div className={styles.tabs}>
        {TABS.map((tab) => (
          <button
            key={tab}
            className={`${styles.tab} ${activeTab === tab ? styles.active : ''}`}
            onClick={() => setActiveTab(tab)}
          >
            {tab}
          </button>
        ))}
      </div>

      <div className={styles.serviceGrid}>
        {services.map((service, index) => (
          <div key={index} className={styles.serviceItem}>
            {service.badge && (
              <span className={`${styles.badge} ${service.badge === 'SPECIAL' ? styles.special : ''}`}>
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
    </main>
  );
}
