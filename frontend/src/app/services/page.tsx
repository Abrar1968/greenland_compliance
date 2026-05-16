'use client';

import { useEffect, useMemo, useState } from 'react';
import { fetchServices } from '@/lib/api';
import type { ServiceCategory } from '@/types/api';
import styles from './services.module.css';

export default function ServicesPage() {
  const [categories, setCategories] = useState<ServiceCategory[]>([]);
  const [activeTab, setActiveTab] = useState('');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchServices()
      .then((items) => {
        setCategories(items);
        setActiveTab(items[0]?.slug ?? '');
      })
      .catch(() => {
        setCategories([]);
      })
      .finally(() => setLoading(false));
  }, []);

  const activeCategory = useMemo(
    () => categories.find((category) => category.slug === activeTab) ?? categories[0],
    [activeTab, categories],
  );

  return (
    <main className={styles.container}>
      <div className={styles.tabs}>
        {categories.map((tab) => (
          <button
            key={tab.slug}
            className={`${styles.tab} ${activeCategory?.slug === tab.slug ? styles.active : ''}`}
            onClick={() => setActiveTab(tab.slug)}
          >
            {tab.label}
          </button>
        ))}
      </div>

      {loading && (
        <div className="text-center py-20 text-gray-400">Loading services...</div>
      )}

      {!loading && !activeCategory && (
        <div className="text-center py-20 text-gray-400">No services are available yet.</div>
      )}

      {activeCategory && (
        <div className={styles.serviceGrid}>
          {activeCategory.services.map((service) => (
            <div key={service.id} className={styles.serviceItem}>
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
      )}
    </main>
  );
}
