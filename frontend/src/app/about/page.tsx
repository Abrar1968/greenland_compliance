'use client';

import { type ReactNode, useEffect, useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import {
  Play,
  ChevronRight,
  Quote,
  Plane,
  TrendingUp,
  ShoppingCart,
  Building2,
  Zap,
  Truck,
  type LucideIcon,
} from 'lucide-react';
import { fetchAbout } from '@/lib/api';
import type {
  AboutData,
  Achievement,
  ApproachCard,
  Faq,
  Partner,
  TeamMember,
  Testimonial as TestimonialType,
  TimelineMilestone,
} from '@/types/api';

type View = 'overview' | 'approach' | 'achievement' | 'partners' | 'team' | 'faq';

const menuItems: { id: View; name: string }[] = [
  { id: 'overview', name: 'Company overview' },
  { id: 'approach', name: 'Our approach' },
  { id: 'achievement', name: 'Our Achievement' },
  { id: 'partners', name: 'Partners' },
  { id: 'team', name: 'Our Team' },
  { id: 'faq', name: 'FAQ' },
];

const iconMap: Record<string, LucideIcon> = {
  Plane,
  TrendingUp,
  ShoppingCart,
  Building2,
  Zap,
  Truck,
  plane: Plane,
  trending_up: TrendingUp,
  shopping_cart: ShoppingCart,
  building: Building2,
  zap: Zap,
  truck: Truck,
};

export default function AboutPage() {
  const [activeView, setActiveView] = useState<View>('overview');
  const [about, setAbout] = useState<AboutData | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAbout()
      .then(setAbout)
      .catch(() => setAbout(null))
      .finally(() => setLoading(false));
  }, []);

  return (
    <main className="pt-[120px] pb-20 bg-white font-sans">
      <div className="w-full h-[60px] mb-12">
        <div className="max-w-[1200px] mx-auto px-6 flex h-full">
          <div className="bg-[#333] text-primary px-10 flex items-center justify-center font-bold text-lg whitespace-nowrap">
            {about?.banner_label ?? ''}
          </div>
          <div className="flex-1 bg-primary"></div>
        </div>
      </div>

      <div className="max-w-[1200px] mx-auto px-6">
        <div className="flex flex-col lg:flex-row gap-12">
          <div className="lg:w-[72%]">
            {loading && (
              <div className="text-center py-20 text-gray-400">Loading about content...</div>
            )}

            {!loading && (
              <>
                <section className="bg-primary rounded-sm p-8 md:p-12 relative overflow-hidden flex flex-col md:flex-row items-center min-h-[350px] mb-12">
                  <div className="md:w-1/2 z-10">
                    <h1 className="text-4xl md:text-5xl font-extrabold text-secondary mb-4 leading-tight">
                      {about?.hero.heading_line1 ?? ''} <br /> {about?.hero.heading_line2 ?? ''}
                    </h1>
                    {about?.hero.paragraph ? (
                      <p className="text-secondary/80 mb-8 max-w-[350px] font-medium">
                        {about.hero.paragraph}
                      </p>
                    ) : null}
                    {about?.hero.cta_label && about?.hero.cta_href ? (
                      <Link
                        href={about.hero.cta_href}
                        className="bg-secondary text-white px-6 py-3 rounded-sm inline-flex items-center gap-2 hover:bg-secondary/90 transition-all font-bold"
                      >
                        {about.hero.cta_label} <ChevronRight size={18} />
                      </Link>
                    ) : null}
                  </div>
                  <div className="md:w-1/2 relative h-[250px] md:h-[300px] w-full mt-8 md:mt-0">
                    {about?.hero.image_url ? (
                      <Image
                        src={about.hero.image_url}
                        alt=""
                        fill
                        className="object-contain"
                      />
                    ) : null}
                  </div>
                  <div className="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
                    <div className="w-2.5 h-2.5 rounded-full bg-white opacity-50"></div>
                    <div className="w-2.5 h-2.5 rounded-full bg-white"></div>
                  </div>
                </section>

                {activeView === 'overview' && <OverviewView about={about} />}
                {activeView === 'approach' && <ApproachView cards={about?.approach.cards ?? []} intro1={about?.approach.intro1} intro2={about?.approach.intro2} />}
                {activeView === 'achievement' && <AchievementView achievements={about?.achievements ?? []} />}
                {activeView === 'partners' && <PartnersView partners={about?.partners ?? []} />}
                {activeView === 'team' && <TeamView team={about?.team ?? []} />}
                {activeView === 'faq' && <FAQView faqs={about?.faqs ?? []} />}
              </>
            )}
          </div>

          <aside className="lg:w-[28%] space-y-8">
            <nav className="bg-gray-50 border border-gray-100">
              <ul className="divide-y divide-gray-200">
                {menuItems.map((item) => (
                  <li key={item.id}>
                    <button
                      onClick={() => setActiveView(item.id)}
                      className={`w-full text-left px-6 py-4 font-bold text-sm transition-colors flex justify-between items-center ${activeView === item.id ? 'bg-white text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:text-primary'}`}
                    >
                      {item.name}
                      <ChevronRight size={16} className={activeView === item.id ? 'text-primary' : 'text-gray-400'} />
                    </button>
                  </li>
                ))}
              </ul>
            </nav>

            <CompanyPresentationLink href={about?.company_presentation_url}>
              <div className="bg-gray-700 p-3 rounded-sm group-hover:bg-primary transition-colors shrink-0">
                <Play size={20} className="fill-white text-white" />
              </div>
              <div>
                <span className="block text-xs uppercase opacity-70 tracking-wider">Download</span>
                <span className="block font-bold text-lg leading-tight">Company presentation</span>
              </div>
            </CompanyPresentationLink>

            <div className="bg-primary p-8 text-white rounded-sm text-center">
              <h4 className="text-xl font-bold mb-4">How can we help you?</h4>
              <p className="text-sm opacity-90 mb-8 leading-relaxed">
                Contact us for your query or submit a business inquiry online.
              </p>
              <Link
                href="/contact"
                className="bg-white text-secondary px-8 py-3 rounded-sm flex items-center justify-center hover:bg-gray-100 transition-all font-bold mx-auto uppercase text-sm"
              >
                Contact Us
              </Link>
            </div>

            <div className="space-y-8">
              {(about?.testimonials ?? []).map((testimonial) => (
                <Testimonial key={testimonial.id} testimonial={testimonial} />
              ))}
            </div>
          </aside>
        </div>

        <section className="mt-20 bg-primary p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6 rounded-sm">
          {about?.footer_cta.text ? (
            <h3 className="text-white text-xl md:text-2xl font-bold text-center md:text-left">
              {about.footer_cta.text}
            </h3>
          ) : null}
          {about?.footer_cta.button_href && about?.footer_cta.button_label ? (
            <Link
              href={about.footer_cta.button_href}
              className="bg-secondary text-white px-8 py-3 rounded-sm flex items-center gap-2 hover:bg-secondary/90 transition-all font-bold whitespace-nowrap"
            >
              {about.footer_cta.button_label} <ChevronRight size={18} />
            </Link>
          ) : null}
        </section>
      </div>
    </main>
  );
}

function CompanyPresentationLink({ href, children }: { href?: string | null; children: ReactNode }) {
  const className = 'w-full bg-secondary text-white p-6 rounded-sm flex items-center gap-4 hover:bg-secondary/95 transition-all group text-left';

  if (href) {
    return (
      <a href={href} target="_blank" rel="noopener noreferrer" className={className}>
        {children}
      </a>
    );
  }

  return (
    <div className={`${className} opacity-70 cursor-not-allowed`} aria-disabled="true">
      {children}
    </div>
  );
}

function OverviewView({ about }: { about: AboutData | null }) {
  const overview = about?.overview;
  const timeline = overview?.timeline ?? [];

  return (
    <>
      <section className="py-16">
        <div className="space-y-12">
          {timeline.map((item) => (
            <TimelineItem key={item.id} item={item} />
          ))}
        </div>
      </section>

      <section className="py-8">
        <h2 className="text-3xl font-bold uppercase text-secondary mb-8 relative inline-block">
          COMPANY OVERVIEW
          <div className="absolute -bottom-2 left-0 w-12 h-1 bg-primary"></div>
        </h2>

        <div className="space-y-6 text-gray-600 leading-relaxed">
          <p>{overview?.paragraph1}</p>
          <p>{overview?.paragraph2}</p>

          <div className="border-l-4 border-primary pl-6 py-4 my-8 bg-gray-50 italic text-secondary font-medium">
            {overview?.callout}
          </div>
        </div>

        <div className="grid md:grid-cols-2 gap-12 mt-12">
          <div>
            {overview?.mission_heading ? (
              <h3 className="text-2xl font-bold text-secondary mb-6">{overview.mission_heading}</h3>
            ) : null}
            {overview?.mission_intro ? (
              <p className="text-gray-600 mb-6">{overview.mission_intro}</p>
            ) : null}
            <ul className="space-y-3">
              {(overview?.mission_bullets ?? []).map((item) => (
                <li key={item.id} className="flex items-start gap-2 text-gray-600 text-sm">
                  <span className="text-primary font-bold mt-[-2px]">•</span>
                  {item.text}
                </li>
              ))}
            </ul>
          </div>
          <div>
            <h3 className="text-2xl font-bold text-secondary mb-6">How we work</h3>
            <div className="aspect-video w-full rounded-sm overflow-hidden shadow-lg bg-black">
              {overview?.how_we_work_video_url ? (
                <iframe
                  width="100%"
                  height="100%"
                  src={overview.how_we_work_video_url}
                  title="How we work video"
                  frameBorder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                  allowFullScreen
                ></iframe>
              ) : null}
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

function ApproachView({ cards, intro1, intro2 }: { cards: ApproachCard[]; intro1?: string; intro2?: string }) {
  return (
    <div className="animate-fadeInUp">
      <h2 className="text-4xl font-extrabold uppercase text-secondary mb-8 relative inline-block">
        OUR APPROACH
        <div className="absolute -bottom-3 left-0 w-16 h-1.5 bg-primary"></div>
      </h2>

      <div className="space-y-6 text-gray-600 leading-relaxed mb-16 text-lg">
        <p>{intro1}</p>
        <p>{intro2}</p>
      </div>

      <div className="grid md:grid-cols-2 gap-y-16 gap-x-12">
        {cards.map((item) => {
          const Icon = iconMap[item.icon] ?? Building2;

          return (
            <div key={item.id} className="flex gap-6 group">
              <div className="shrink-0 relative">
                <div
                  className="w-16 h-16 bg-white border-2 border-secondary flex items-center justify-center group-hover:bg-primary group-hover:border-primary transition-all duration-300"
                  style={{ clipPath: 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)' }}
                >
                  <Icon size={28} className="text-secondary group-hover:text-white transition-colors" />
                </div>
              </div>
              <div>
                <h4 className="text-xl font-bold text-secondary mb-3 group-hover:text-primary transition-colors leading-tight">
                  {item.title}
                </h4>
                <p className="text-gray-500 text-[15px] leading-relaxed">
                  {item.description}
                </p>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}

function AchievementView({ achievements }: { achievements: Achievement[] }) {
  return (
    <div className="animate-fadeInUp">
      <h2 className="text-4xl font-extrabold uppercase text-secondary mb-12 relative inline-block">
        OUR ACHIEVEMENT
        <div className="absolute -bottom-3 left-0 w-16 h-1.5 bg-primary"></div>
      </h2>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-12">
        {achievements.map((cert) => (
          <div key={cert.id} className="group">
            <div className="mb-6">
              <h4 className="text-xl font-bold text-secondary mb-2 flex items-center gap-2 group-hover:text-primary transition-colors">
                {cert.title}
              </h4>
              <div className="w-10 h-1 bg-primary group-hover:w-16 transition-all duration-300"></div>
            </div>

            <div className="aspect-[4/3] bg-gray-50 border border-gray-200 rounded-sm overflow-hidden relative shadow-sm group-hover:shadow-md transition-shadow">
              {cert.image_url ? (
                <Image
                  src={cert.image_url}
                  alt={cert.title}
                  fill
                  sizes="(max-width: 768px) 100vw, 50vw"
                  className="object-cover group-hover:scale-105 transition-transform duration-500"
                />
              ) : (
                <div className="absolute inset-0 flex items-center justify-center">
                  <span className="text-gray-300 font-medium italic">Certificate Image</span>
                </div>
              )}
              <div className="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function PartnersView({ partners }: { partners: Partner[] }) {
  return (
    <div className="animate-fadeInUp space-y-16">
      {partners.map((partner) => (
        <div key={partner.id} className="group">
          <div className="mb-6">
            <h3 className="text-3xl font-bold text-secondary mb-1 group-hover:text-primary transition-colors">
              {partner.name}
            </h3>
            <div className="flex flex-col text-gray-400 text-sm font-medium">
              {partner.industry && <span>{partner.industry}</span>}
              {partner.location && <span>{partner.location}</span>}
            </div>
          </div>
          <div className="w-full h-px bg-gray-100 mb-8"></div>
          <p className="text-gray-500 leading-relaxed text-lg">
            {partner.description}
          </p>
        </div>
      ))}
    </div>
  );
}

function TeamView({ team }: { team: TeamMember[] }) {
  return (
    <div className="animate-fadeInUp">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-16">
        {team.map((member) => (
          <div key={member.id} className="flex gap-6 group">
            <div className="shrink-0">
              <div className="w-32 h-32 rounded-full border-2 border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden group-hover:border-primary transition-colors relative">
                {member.image_url ? (
                  <Image src={member.image_url} alt={member.name} fill sizes="128px" className="object-cover" />
                ) : (
                  <div className="text-gray-300 italic text-xs">Image Frame</div>
                )}
              </div>
            </div>
            <div className="flex flex-col">
              <h3 className="text-2xl font-bold text-secondary mb-1 leading-tight group-hover:text-primary transition-colors">
                {member.name}
              </h3>
              <span className="text-primary font-semibold text-sm mb-4 uppercase tracking-wide">
                {member.role}
              </span>
              <p className="text-gray-500 text-sm leading-relaxed mb-4">
                {member.description}
              </p>
              <button className="text-secondary font-bold text-sm flex items-center gap-2 hover:text-primary transition-colors uppercase tracking-tight">
                view profile <ChevronRight size={14} className="mt-0.5" />
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function FAQView({ faqs }: { faqs: Faq[] }) {
  const [openIdx, setOpenIdx] = useState<number | null>(0);

  return (
    <div className="animate-fadeInUp">
      <h2 className="text-4xl font-extrabold uppercase text-secondary mb-8 relative inline-block">
        FAQ
        <div className="absolute -bottom-3 left-0 w-16 h-1.5 bg-primary"></div>
      </h2>

      <div className="space-y-4">
        {faqs.map((faq, idx) => (
          <div key={faq.id} className="border border-gray-100 rounded-sm overflow-hidden">
            <button
              onClick={() => setOpenIdx(openIdx === idx ? null : idx)}
              className="w-full flex items-center gap-4 px-6 py-5 text-left bg-white hover:bg-gray-50 transition-colors group"
            >
              <div className="shrink-0 w-5 h-5 flex items-center justify-center font-bold text-lg text-secondary group-hover:text-primary">
                {openIdx === idx ? '-' : '+'}
              </div>
              <span className="font-bold text-secondary text-lg group-hover:text-primary transition-colors">
                {faq.question}
              </span>
            </button>

            <div
              className={`grid transition-all duration-300 ease-in-out ${openIdx === idx ? 'grid-rows-[1fr] opacity-100 py-6' : 'grid-rows-[0fr] opacity-0'}`}
            >
              <div className="overflow-hidden px-14">
                <p className="text-gray-500 leading-relaxed">
                  {faq.answer}
                </p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function TimelineItem({ item }: { item: TimelineMilestone }) {
  return (
    <div className="flex gap-8 relative">
      <div className="text-2xl font-bold text-secondary w-16 pt-1 shrink-0">{item.year}</div>
      <div className="relative flex flex-col items-center">
        <div className="w-3 h-3 rounded-full bg-primary mt-3 z-10"></div>
        <div className="absolute top-4 bottom-[-48px] w-[2px] bg-gray-200"></div>
      </div>
      <div className="flex-1 pb-12">
        <h4 className="text-xl font-bold text-secondary mb-3">{item.title}</h4>
        <p className="text-gray-500 text-sm leading-relaxed">{item.description}</p>
      </div>
    </div>
  );
}

function Testimonial({ testimonial }: { testimonial: TestimonialType }) {
  return (
    <div className="bg-white border border-gray-100 p-6 shadow-sm rounded-sm relative">
      <div className="mb-4">
        <p className="text-gray-500 text-sm italic leading-relaxed">
          &quot;{testimonial.quote}&quot;
        </p>
      </div>
      <div className="absolute -bottom-2 right-4 text-primary opacity-20">
        <Quote size={40} fill="currentColor" />
      </div>
      <div className="flex items-center gap-3 mt-4">
        <div className="w-12 h-12 rounded-full overflow-hidden border border-gray-200 shrink-0 relative">
          {testimonial.avatar_url ? (
            <Image src={testimonial.avatar_url} alt={testimonial.author} fill sizes="48px" className="object-cover" />
          ) : (
            <div className="w-full h-full bg-gray-200" />
          )}
        </div>
        <div>
          <h5 className="font-bold text-secondary text-sm">{testimonial.author}</h5>
          <p className="text-xs text-gray-400">{testimonial.role}</p>
        </div>
      </div>
    </div>
  );
}
