'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { ArrowRight } from 'lucide-react';
import { apiFallbacks } from '@/lib/api';
import type { HeroData } from '@/types/api';

type HeroProps = {
  data: HeroData | null;
};

export default function Hero({ data }: HeroProps) {
  const [currentSlide, setCurrentSlide] = useState(0);
  const slides = data?.slides?.length ? data.slides : apiFallbacks.heroSlides;

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % slides.length);
    }, 5000);
    return () => clearInterval(interval);
  }, [slides.length]);

  return (
    <section className="relative h-screen min-h-[600px] w-full overflow-hidden flex items-center justify-start text-white">
      {/* Slides */}
      {slides.map((slide, index) => (
        <div
          key={slide.id}
          className={`absolute inset-0 transition-opacity duration-[1500ms] ease-in-out -z-20 ${index === currentSlide ? 'opacity-100' : 'opacity-0'
            }`}
        >
          <Image
            src={slide.image_url}
            alt={slide.alt_text || `Hero slide ${index + 1}`}
            fill
            priority={index === 0}
            className="object-cover"
            quality={75}
          />
        </div>
      ))}

      {/* Overlay */}
      <div className="absolute inset-0 bg-gradient-to-r from-black/80 to-black/40 bg-[#79b940]/10 -z-10" />

      {/* Content */}
      <div className="max-w-[1200px] mx-auto px-6 w-full z-10 animate-fadeInUp">
        <h1 className="text-3xl md:text-5xl lg:text-6xl leading-tight mb-4 uppercase tracking-tighter">
          <span className="font-light block mb-1">{data?.headline_line1 ?? 'Your Vision, Our Compliance.'}</span>
          <span className="font-black">{data?.headline_line2 ?? 'Building Sustainable Business Foundations in Bangladesh'}</span>
        </h1>
        <p className="text-base md:text-lg leading-relaxed mb-8 text-white/90 max-w-2xl font-medium">
          {data?.paragraph ??
            'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. We handle the complexity so you can lead with confidence.'}
        </p>
        <div className="flex gap-6 items-center flex-wrap">
          <Link
            href={data?.cta1_href ?? '/contact'}
            className="bg-primary text-white py-4 px-8 rounded font-extrabold uppercase text-sm transition-all duration-300 shadow-lg shadow-primary/30 hover:bg-green-700 hover:-translate-y-0.5"
          >
            {data?.cta1_label ?? 'Book a Consultation'}
          </Link>
          <Link
            href={data?.cta2_href ?? '/services'}
            className="text-white font-bold text-lg flex items-center gap-2 transition-all duration-300 hover:text-primary hover:translate-x-1"
          >
            {data?.cta2_label ?? 'Explore Our Services'} <ArrowRight size={18} />
          </Link>
        </div>
      </div>
    </section>
  );
}
