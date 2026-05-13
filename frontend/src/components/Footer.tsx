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
  ExternalLink
} from 'lucide-react';

export default function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-[#1a1a1a] text-white pt-20 pb-10 font-sans">
      <div className="max-w-[1200px] mx-auto px-6">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          
          {/* Column 1: Company Info */}
          <div className="space-y-6">
            <Link href="/">
              <Image 
                src="/logo/gc.png" 
                alt="Greenland Business & Compliance" 
                width={200} 
                height={50} 
                className="object-contain brightness-0 invert"
              />
            </Link>
            <p className="text-gray-400 text-sm leading-relaxed">
              Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. 
              Building sustainable business foundations in Bangladesh since 1985.
            </p>
            <div className="flex gap-4">
              <a href="#" className="w-10 h-10 rounded-full bg-[#333] flex items-center justify-center hover:bg-primary transition-colors" title="LinkedIn">
                <Users size={18} />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-[#333] flex items-center justify-center hover:bg-primary transition-colors" title="Facebook">
                <Share2 size={18} />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-[#333] flex items-center justify-center hover:bg-primary transition-colors" title="Twitter">
                <Globe size={18} />
              </a>
              <a href="#" className="w-10 h-10 rounded-full bg-[#333] flex items-center justify-center hover:bg-primary transition-colors" title="Instagram">
                <Camera size={18} />
              </a>
            </div>
          </div>

          {/* Column 2: Quick Links */}
          <div>
            <h4 className="text-lg font-bold mb-8 uppercase tracking-wider relative inline-block">
              Quick Links
              <span className="absolute -bottom-2 left-0 w-8 h-1 bg-primary"></span>
            </h4>
            <ul className="space-y-4 text-gray-400 text-sm font-medium">
              {[
                { name: 'Home', href: '/' },
                { name: 'About Us', href: '/about' },
                { name: 'Our Services', href: '/services' },
                { name: 'Case Studies', href: '/case-studies' },
                { name: 'Resources', href: '/resources' },
                { name: 'Contact Us', href: '/contact' },
              ].map((link) => (
                <li key={link.href}>
                  <Link href={link.href} className="hover:text-primary hover:translate-x-1 transition-all flex items-center gap-2">
                    <ArrowRight size={14} className="text-primary" />
                    {link.name}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Column 3: Services */}
          <div>
            <h4 className="text-lg font-bold mb-8 uppercase tracking-wider relative inline-block">
              Our Services
              <span className="absolute -bottom-2 left-0 w-8 h-1 bg-primary"></span>
            </h4>
            <ul className="space-y-4 text-gray-400 text-sm font-medium">
              {[
                'Business Advisory',
                'Audit & Assurance',
                'Taxation Services',
                'Regulatory Compliance',
                'Human Capital Management',
                'Strategy Consulting',
              ].map((service) => (
                <li key={service}>
                  <Link href="/services" className="hover:text-primary hover:translate-x-1 transition-all flex items-center gap-2">
                    <ArrowRight size={14} className="text-primary" />
                    {service}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Column 4: Contact Info */}
          <div>
            <h4 className="text-lg font-bold mb-8 uppercase tracking-wider relative inline-block">
              Contact Us
              <span className="absolute -bottom-2 left-0 w-8 h-1 bg-primary"></span>
            </h4>
            <ul className="space-y-6 text-gray-400 text-sm">
              <li className="flex items-start gap-4">
                <MapPin size={20} className="text-primary shrink-0 mt-1" />
                <span>Bottola Bazar, Bhakurta, Savar, Dhaka-1313, Bangladesh.</span>
              </li>
              <li className="flex items-center gap-4">
                <Phone size={20} className="text-primary shrink-0" />
                <span>+8801987-644603</span>
              </li>
              <li className="flex items-center gap-4">
                <Mail size={20} className="text-primary shrink-0" />
                <span>contact@greenlandcompliance.com</span>
              </li>
            </ul>
          </div>

        </div>

        {/* CTA Row */}
        <div className="bg-[#222] p-8 rounded-sm mb-16 flex flex-col lg:flex-row items-center justify-between gap-8 border-l-4 border-primary">
          <div className="max-w-xl">
            <h3 className="text-xl font-bold mb-2 uppercase tracking-tight">Ready to take your business to the next level?</h3>
            <p className="text-gray-400 text-sm">Our expert consultants are ready to help you navigate the complexities of compliance and growth in Bangladesh.</p>
          </div>
          <div className="flex w-full lg:w-auto">
            <Link 
              href="/contact"
              className="bg-primary text-white px-10 py-4 rounded-sm font-extrabold uppercase text-xs hover:bg-green-700 transition-all hover:scale-[1.02] active:scale-[0.98] whitespace-nowrap text-center w-full lg:w-auto shadow-lg shadow-primary/20"
            >
              Request a Free Quote
            </Link>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="border-t border-gray-800 pt-10 flex flex-col md:flex-row justify-between items-center gap-6">
          <p className="text-gray-500 text-xs">
            © {currentYear} Greenland Business & Compliance. All Rights Reserved.
          </p>
          <div className="flex gap-8 text-gray-500 text-xs font-medium">
            <Link href="/privacy" className="hover:text-primary transition-colors">Privacy Policy</Link>
            <Link href="/terms" className="hover:text-primary transition-colors">Terms of Service</Link>
            <Link href="/cookies" className="hover:text-primary transition-colors">Cookie Settings</Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
