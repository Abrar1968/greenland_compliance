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
import type { Navigation, SiteSettings, SocialLink } from '@/types/api';

type FooterProps = {
  site: SiteSettings | null;
  nav: Navigation | null;
};


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
  const logo = site?.logo_url ?? null;
  const siteName = site?.site_name ?? '';
  const quickLinks = nav?.footer_quick ?? [];
  const serviceLinks = nav?.footer_services ?? [];
  const policyLinks = nav?.footer_policy ?? [];
  const socialLinks = site?.social_links ?? [];
  const showCta = Boolean(
    site?.footer_cta_title ||
    site?.footer_cta_text ||
    site?.footer_cta_button_label ||
    site?.footer_cta_button_href
  );

  return (
    <footer className="bg-[#1a1a1a] text-white pt-20 pb-10 font-sans">
      <div className="max-w-[1200px] mx-auto px-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

          <div className="space-y-6">
            <Link href="/">
              {logo ? (
                <Image
                  src={logo}
                  alt={siteName}
                  width={200}
                  height={50}
                  className="object-contain brightness-0 invert"
                />
              ) : siteName ? (
                <span className="text-sm font-bold text-white uppercase tracking-wide">
                  {siteName}
                </span>
              ) : null}
            </Link>
            {site?.footer_description ? (
              <p className="text-gray-400 text-sm leading-relaxed">
                {site.footer_description}
              </p>
            ) : null}
            <div className="flex gap-4">
              {socialLinks.map((link) => (
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
              ))}
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
              {site?.address ? (
                <li className="flex items-start gap-4">
                  <MapPin size={20} className="text-primary shrink-0 mt-1" />
                  <span>{site.address}</span>
                </li>
              ) : null}
              {site?.primary_phone ? (
                <li className="flex items-center gap-4">
                  <Phone size={20} className="text-primary shrink-0" />
                  <span>{site.primary_phone}</span>
                </li>
              ) : null}
              {site?.primary_email ? (
                <li className="flex items-center gap-4">
                  <Mail size={20} className="text-primary shrink-0" />
                  <span>{site.primary_email}</span>
                </li>
              ) : null}
            </ul>
          </div>

        </div>

        {showCta ? (
          <div className="bg-[#222] p-8 rounded-sm mb-16 flex flex-col lg:flex-row items-center justify-between gap-8 border-l-4 border-primary">
            <div className="max-w-xl">
              {site?.footer_cta_title ? (
                <h3 className="text-xl font-bold mb-2 uppercase tracking-tight">
                  {site.footer_cta_title}
                </h3>
              ) : null}
              {site?.footer_cta_text ? (
                <p className="text-gray-400 text-sm">
                  {site.footer_cta_text}
                </p>
              ) : null}
            </div>
            {site?.footer_cta_button_href && site?.footer_cta_button_label ? (
              <div className="flex w-full lg:w-auto">
                <Link
                  href={site.footer_cta_button_href}
                  className="bg-primary text-white px-10 py-4 rounded-sm font-extrabold uppercase text-xs hover:bg-green-700 transition-all hover:scale-[1.02] active:scale-[0.98] whitespace-nowrap text-center w-full lg:w-auto shadow-lg shadow-primary/20"
                >
                  {site.footer_cta_button_label}
                </Link>
              </div>
            ) : null}
          </div>
        ) : null}

        <div className="border-t border-gray-800 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
          <p className="text-gray-500 text-xs">
            {site?.copyright_text ?? ''}
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
