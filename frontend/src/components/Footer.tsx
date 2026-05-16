'use client';

import Link from 'next/link';
import Image from 'next/image';
import {
  MapPin,
  Phone,
  Mail,
  Share2,
  Globe,
  Users,
  Camera,
  ArrowRight,
} from 'lucide-react';
import { apiFallbacks } from '@/lib/api';
import type { NavItem, Navigation, SiteSettings, SocialLink } from '@/types/api';

type FooterProps = {
  site: SiteSettings | null;
  nav: Navigation | null;
};

const fallbackQuickLinks: NavItem[] = [
  { id: 1, label: 'Home', href: '/' },
  { id: 2, label: 'About Us', href: '/about' },
  { id: 3, label: 'Our Services', href: '/services' },
  { id: 4, label: 'Case Studies', href: '/case-studies' },
  { id: 5, label: 'Resources', href: '/resources' },
  { id: 6, label: 'Contact Us', href: '/contact' },
];

const fallbackServices: NavItem[] = [
  { id: 1, label: 'Business Advisory', href: '/services' },
  { id: 2, label: 'Audit & Assurance', href: '/services' },
  { id: 3, label: 'Taxation Services', href: '/services' },
  { id: 4, label: 'Regulatory Compliance', href: '/services' },
  { id: 5, label: 'Human Capital Management', href: '/services' },
  { id: 6, label: 'Strategy Consulting', href: '/services' },
];

const fallbackPolicyLinks: NavItem[] = [
  { id: 1, label: 'Privacy Policy', href: '/privacy' },
  { id: 2, label: 'Terms of Service', href: '/terms' },
  { id: 3, label: 'Cookie Settings', href: '/cookies' },
];

function socialIcon(platform: string) {
  const lower = platform.toLowerCase();
  if (lower.includes('linkedin')) return <Users size={18} />;
  if (lower.includes('facebook')) return <Share2 size={18} />;
  if (lower.includes('instagram')) return <Camera size={18} />;
  return <Globe size={18} />;
}

function socialTitle(link: SocialLink) {
  return link.platform.charAt(0).toUpperCase() + link.platform.slice(1);
}

export default function Footer({ site, nav }: FooterProps) {
  const logo = site?.logo_url ?? apiFallbacks.logo;
  const siteName = site?.site_name ?? 'Greenland Business & Compliance';
  const quickLinks = nav?.footer_quick?.length ? nav.footer_quick : fallbackQuickLinks;
  const serviceLinks = nav?.footer_services?.length ? nav.footer_services : fallbackServices;
  const policyLinks = nav?.footer_policy?.length ? nav.footer_policy : fallbackPolicyLinks;
  const socialLinks = site?.social_links ?? [];

  return (
    <footer className="bg-[#1a1a1a] text-white pt-20 pb-10 font-sans">
      <div className="max-w-[1200px] mx-auto px-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

          <div className="space-y-6">
            <Link href="/">
              <Image
                src={logo}
                alt={siteName}
                width={200}
                height={50}
                className="object-contain brightness-0 invert"
              />
            </Link>
            <p className="text-gray-400 text-sm leading-relaxed">
              {site?.footer_description ??
                'Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. Building sustainable business foundations in Bangladesh since 1985.'}
            </p>
            <div className="flex gap-4">
              {socialLinks.length > 0 ? (
                socialLinks.map((link) => (
                  <a
                    key={`${link.platform}-${link.url}`}
                    href={link.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-10 h-10 rounded-full bg-[#333] flex items-center justify-center hover:bg-primary transition-colors"
                    title={socialTitle(link)}
                  >
                    {socialIcon(link.platform)}
                  </a>
                ))
              ) : (
                ['LinkedIn', 'Facebook', 'Twitter', 'Instagram'].map((platform) => (
                  <span
                    key={platform}
                    className="w-10 h-10 rounded-full bg-[#333] flex items-center justify-center text-gray-500"
                    title={platform}
                  >
                    {socialIcon(platform)}
                  </span>
                ))
              )}
            </div>
          </div>

          <div>
            <h4 className="text-lg font-bold mb-8 uppercase tracking-wider relative inline-block">
              Quick Links
              <span className="absolute -bottom-2 left-0 w-8 h-1 bg-primary"></span>
            </h4>
            <ul className="space-y-4 text-gray-400 text-sm font-medium">
              {quickLinks.map((link) => (
                <li key={link.id}>
                  <Link href={link.href} className="hover:text-primary hover:translate-x-1 transition-all flex items-center gap-2">
                    <ArrowRight size={14} className="text-primary" />
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="text-lg font-bold mb-8 uppercase tracking-wider relative inline-block">
              Our Services
              <span className="absolute -bottom-2 left-0 w-8 h-1 bg-primary"></span>
            </h4>
            <ul className="space-y-4 text-gray-400 text-sm font-medium">
              {serviceLinks.map((service) => (
                <li key={service.id}>
                  <Link href={service.href} className="hover:text-primary hover:translate-x-1 transition-all flex items-center gap-2">
                    <ArrowRight size={14} className="text-primary" />
                    {service.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h4 className="text-lg font-bold mb-8 uppercase tracking-wider relative inline-block">
              Contact Us
              <span className="absolute -bottom-2 left-0 w-8 h-1 bg-primary"></span>
            </h4>
            <ul className="space-y-6 text-gray-400 text-sm">
              <li className="flex items-start gap-4">
                <MapPin size={20} className="text-primary shrink-0 mt-1" />
                <span>{site?.address ?? 'Bottola Bazar, Bhakurta, Savar, Dhaka-1313, Bangladesh.'}</span>
              </li>
              <li className="flex items-center gap-4">
                <Phone size={20} className="text-primary shrink-0" />
                <span>{site?.primary_phone ?? '+8801987-644603'}</span>
              </li>
              <li className="flex items-center gap-4">
                <Mail size={20} className="text-primary shrink-0" />
                <span>{site?.primary_email ?? 'contact@greenlandcompliance.com'}</span>
              </li>
            </ul>
          </div>

        </div>

        <div className="bg-[#222] p-8 rounded-sm mb-16 flex flex-col lg:flex-row items-center justify-between gap-8 border-l-4 border-primary">
          <div className="max-w-xl">
            <h3 className="text-xl font-bold mb-2 uppercase tracking-tight">
              {site?.footer_cta_title ?? 'Ready to take your business to the next level?'}
            </h3>
            <p className="text-gray-400 text-sm">
              {site?.footer_cta_text ?? 'Our expert consultants are ready to help you navigate the complexities of compliance and growth in Bangladesh.'}
            </p>
          </div>
          <div className="flex w-full lg:w-auto">
            <Link
              href={site?.footer_cta_button_href ?? '/contact'}
              className="bg-primary text-white px-10 py-4 rounded-sm font-extrabold uppercase text-xs hover:bg-green-700 transition-all hover:scale-[1.02] active:scale-[0.98] whitespace-nowrap text-center w-full lg:w-auto shadow-lg shadow-primary/20"
            >
              {site?.footer_cta_button_label ?? 'Request a Free Quote'}
            </Link>
          </div>
        </div>

        <div className="border-t border-gray-800 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
          <p className="text-gray-500 text-xs">
            {site?.copyright_text ?? `© ${new Date().getFullYear()} Greenland Business & Compliance. All Rights Reserved.`}
          </p>
          <div className="flex gap-8 text-gray-500 text-xs font-medium">
            {policyLinks.map((link) => (
              <Link key={link.id} href={link.href} className="hover:text-primary transition-colors">
                {link.label}
              </Link>
            ))}
          </div>
        </div>
      </div>
    </footer>
  );
}
