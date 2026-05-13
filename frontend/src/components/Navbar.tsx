'use client';

import { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { usePathname } from 'next/navigation';
import { MapPin, Clock, Phone, Search, Menu, X } from 'lucide-react';

export default function Navbar() {
  const pathname = usePathname();
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  const navLinks = [
    { name: 'Home', href: '/' },
    { name: 'Services', href: '/services' },
    { name: 'Case Studies', href: '/case-studies' },
    { name: 'About Us', href: '/about' },
    { name: 'Contact US', href: '/contact' },
    { name: 'Resources', href: '/resources' },
  ];

  return (
    <div className="w-full font-sans fixed top-0 left-0 z-[1000]">
      {/* Top Bar */}
      <div className="bg-[#222] text-white py-2 hidden md:block text-[12px]">
        <div className="max-w-[1200px] mx-auto px-6 flex justify-between items-center">
          <div className="flex gap-6">
            <div className="flex items-center gap-2">
              <MapPin size={14} className="text-primary" />
              <span className="text-gray-300">Bottola Bazar, Bhakurta, Savar, Dhaka-1313.</span>
            </div>
            <div className="flex items-center gap-2">
              <Clock size={14} className="text-primary" />
              <span className="text-gray-300">Mon to Sat 8 am to 10 pm | Sunday CLOSED</span>
            </div>
            <div className="flex items-center gap-2">
              <Phone size={14} className="text-primary" />
              <span className="text-gray-300">+8801987-644603 (Call Now)</span>
            </div>
          </div>
          <div className="cursor-pointer">
            <Search size={18} />
          </div>
        </div>
      </div>

      {/* Main Nav */}
      <nav className="bg-white py-2 shadow-sm relative">
        <div className="max-w-[1200px] mx-auto px-6 flex justify-between items-center">
          <div className="flex items-center gap-2 py-1">
          <Link href="/" onClick={() => setIsMenuOpen(false)}>
            <Image 
              src="/logo/gc.png" 
              alt="Greenland Business & Compliance" 
              width={250} 
              height={60} 
              priority
              className="object-contain w-[180px] md:w-[250px]"
            />
          </Link>
        </div>

        {/* Desktop Menu */}
        <ul className="hidden lg:flex list-none gap-8">
          {navLinks.map((link) => (
            <li key={link.href}>
              <Link 
                href={link.href} 
                className={`text-sm font-bold uppercase transition-colors duration-300 ${
                  pathname === link.href ? 'text-primary' : 'text-gray-800 hover:text-primary'
                }`}
              >
                {link.name}
              </Link>
            </li>
          ))}
        </ul>

        {/* Mobile Hamburger Icon */}
        <button 
          className="lg:hidden text-gray-800 p-2"
          onClick={() => setIsMenuOpen(!isMenuOpen)}
          aria-label="Toggle Menu"
        >
          {isMenuOpen ? <X size={28} /> : <Menu size={28} />}
        </button>

        {/* Mobile Menu Overlay */}
        {isMenuOpen && (
          <div className="absolute top-full left-0 w-full bg-white border-t border-gray-100 shadow-lg lg:hidden animate-in fade-in slide-in-from-top-2 duration-300">
            <ul className="flex flex-col list-none p-6 gap-4">
              {navLinks.map((link) => (
                <li key={link.href}>
                  <Link 
                    href={link.href} 
                    onClick={() => setIsMenuOpen(false)}
                    className={`text-base font-bold uppercase block py-2 transition-colors duration-300 ${
                      pathname === link.href ? 'text-primary' : 'text-gray-800 hover:text-primary'
                    }`}
                  >
                    {link.name}
                  </Link>
                </li>
              ))}
              {/* Mobile contact info */}
              <li className="mt-4 pt-4 border-t border-gray-100 space-y-3">
                <div className="flex items-center gap-3 text-xs text-gray-500">
                  <Phone size={14} className="text-primary" />
                  <span>+8801987-644603</span>
                </div>
                <div className="flex items-center gap-3 text-xs text-gray-500">
                  <MapPin size={14} className="text-primary" />
                  <span>Savar, Dhaka-1313</span>
                </div>
              </li>
            </ul>
          </div>
        )}
        </div>
      </nav>
    </div>
  );
}
