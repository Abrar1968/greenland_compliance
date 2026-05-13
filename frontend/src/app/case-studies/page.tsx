'use client';

import Link from 'next/link';
import { Play, Quote } from 'lucide-react';
import styles from './case-studies.module.css';

const CASE_STUDIES = [
  {
    category: 'Business Services',
    title: 'Healthcare giant overcomes merger in 2015',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Healthcare'
  },
  {
    category: 'Travel & Aviation',
    title: 'Focus on core delivers growth for retailer trading',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Travel'
  },
  {
    category: 'Business Services',
    title: 'Transformation sparks financial income for all',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Transformation'
  },
  {
    category: 'Business Services',
    title: 'Increased sales productivity frees selling time and saves millions',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Productivity'
  },
  {
    category: 'Energy & Environment',
    title: 'Constructing a best-in-class global procurement',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Energy'
  },
  {
    category: 'Business Services',
    title: 'Turning around a reactive pharma supply chain',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Pharma'
  },
  {
    category: 'Financial Services',
    title: 'Leading consumer products companies',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Financial'
  },
  {
    category: 'Surface Transport & Logistics',
    title: 'Bain helps transportation & logistics companies',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Logistics'
  },
  {
    category: 'Energy & Environment',
    title: 'Developing a strategy and roadmap for clients',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Strategy'
  },
  {
    category: 'Business Services',
    title: 'Constructing the best-in-class global',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Global'
  },
  {
    category: 'Surface Transport & Logistics',
    title: 'Demand as transportation services as',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Demand'
  },
  {
    category: 'Consumer Products',
    title: 'Pricing games: A technology company',
    image: 'https://placehold.co/600x450/f3f4f6/666?text=Pricing'
  }
];

export default function CaseStudiesPage() {
  return (
    <main className={styles.container}>
      <div className={styles.layout}>
        {/* Main Content - Grid of Case Studies */}
        <div className={styles.mainContent}>
          <div className={styles.grid}>
            {CASE_STUDIES.map((study, index) => (
              <div key={index} className={styles.caseItem}>
                <div className={styles.imageWrapper}>
                  <div className="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 font-bold italic">
                    img here
                  </div>
                </div>
                <span className={styles.category}>{study.category}</span>
                <h3 className={styles.title}>{study.title}</h3>
              </div>
            ))}
          </div>
        </div>

        {/* Sidebar - Shared elements from About Us */}
        <aside className={styles.sidebar}>
          <div className="space-y-8">
            {/* Download Presentation */}
            <button className={styles.downloadBox}>
              <div className={styles.iconCircle}>
                <Play size={20} className="fill-white text-white ml-1" />
              </div>
              <div>
                <span className={styles.downloadLabel}>Download</span>
                <div className={styles.downloadTitle}>Company presentation</div>
              </div>
            </button>

            {/* Help Box */}
            <div className={styles.helpBox}>
              <h4 className={styles.helpTitle}>How can we help you?</h4>
              <p className={styles.helpDesc}>
                Contact us for your query or submit a business inquiry online.
              </p>
              <Link href="/contact" className={styles.contactBtn}>
                CONTACT US
              </Link>
            </div>

            {/* Testimonials */}
            <div className="space-y-6">
              <Testimonial
                quote="The results were clear, professional, and persuasive, and the investors and advisors who have seen the materials loved them. They know what investors want."
                author="Damian Smulders"
                role="CEO, TechFlow"
              />
              <Testimonial
                quote="We thought a lot before choosing the Financial WordPress Theme because we wanted to sure our investment would yield results. Consulting theme is an invaluable partner."
                author="Cintia Le Cane"
                role="Chairman, Harmony Corporation"
              />
              <Testimonial
                quote="We were amazed by how little effort was required on our part to have Consulting WP prepare these materials. We exchanged a few phone calls. Consulting theme is an invaluable partner."
                author="Amanda Seyford"
                role="Founder & CEO, Arcade Systems"
              />
            </div>
          </div>
        </aside>
      </div>
    </main>
  );
}

function Testimonial({ quote, author, role }: { quote: string; author: string; role: string }) {
  return (
    <div className={styles.testimonial}>
      <p className={styles.quoteText}>&quot;{quote}&quot;</p>
      <div className={styles.quoteIcon}>
        <Quote size={48} fill="currentColor" />
      </div>
      <div className={styles.authorRow}>
        <div className={styles.avatar}>
          {/* Avatar placeholder */}
          <div className="w-full h-full bg-gray-200" />
        </div>
        <div>
          <div className={styles.authorName}>{author}</div>
          <div className={styles.authorRole}>{role}</div>
        </div>
      </div>
    </div>
  );
}
