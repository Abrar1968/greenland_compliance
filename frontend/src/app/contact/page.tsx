'use client';

import { FormEvent, useEffect, useState } from 'react';
import Image from 'next/image';
import { MapPin, Phone, Mail, Globe, MessageSquare, ArrowRight, Share2, Users, Camera } from 'lucide-react';
import { fetchContactInfo, submitContactForm } from '@/lib/api';
import type { ContactInfo, ContactPayload, SocialLink } from '@/types/api';

const initialForm: ContactPayload = {
  first_name: '',
  email: '',
  phone: '',
  message: '',
};

function socialIcon(platform: string) {
  const lower = platform.toLowerCase();
  if (lower.includes('facebook')) return <Share2 size={18} />;
  if (lower.includes('linkedin')) return <Users size={18} />;
  if (lower.includes('instagram')) return <Camera size={18} />;
  if (lower.includes('whatsapp')) return <MessageSquare size={18} />;
  return <Globe size={18} />;
}

function socialTitle(link: SocialLink) {
  return link.platform.charAt(0).toUpperCase() + link.platform.slice(1);
}

export default function ContactPage() {
  const [contact, setContact] = useState<ContactInfo | null>(null);
  const [form, setForm] = useState<ContactPayload>(initialForm);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [success, setSuccess] = useState('');
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    fetchContactInfo()
      .then(setContact)
      .catch(() => setContact(null));
  }, []);

  async function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setSubmitting(true);
    setErrors({});
    setSuccess('');

    const result = await submitContactForm(form);

    if (result.ok) {
      setSuccess(result.message);
      setForm(initialForm);
    } else {
      setErrors(result.errors);
    }

    setSubmitting(false);
  }

  return (
    <main className="min-h-screen pt-[120px] pb-20 bg-white font-sans">
      <div className="w-full h-[60px] mb-12">
        <div className="max-w-[1200px] mx-auto px-6 flex h-full">
          <div className="bg-[#333] text-primary px-10 flex items-center justify-center font-bold text-lg whitespace-nowrap">
            {contact?.banner_label ?? 'Our Office'}
          </div>
          <div className="flex-1 bg-primary"></div>
        </div>
      </div>

      <div className="max-w-[1200px] mx-auto px-6">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
          <div className="bg-gray-100 border-2 border-dashed border-gray-300 rounded-sm h-[350px] flex items-center justify-center relative overflow-hidden group">
            {contact?.office_image_url ? (
              <Image
                src={contact.office_image_url}
                alt="Greenland Compliance office"
                fill
                sizes="(max-width: 768px) 100vw, 33vw"
                className="object-cover"
              />
            ) : (
              <span className="text-gray-400 font-medium">Image Placeholder</span>
            )}
          </div>

          <div className="h-[350px] w-full rounded-sm overflow-hidden border border-gray-200">
            {contact?.map_embed_url ? (
              <iframe
                src={contact.map_embed_url}
                width="100%"
                height="100%"
                style={{ border: 0 }}
                allowFullScreen
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
              ></iframe>
            ) : null}
          </div>

          <div className="bg-[#0b132b] text-white p-10 rounded-sm flex flex-col justify-between">
            <div>
              <h2 className="text-2xl font-bold mb-8">Contact Details</h2>
              <ul className="space-y-6">
                <li className="flex items-start gap-4">
                  <MapPin className="text-primary mt-1 shrink-0" size={20} />
                  <span className="text-gray-300">{contact?.address ?? 'Bottola Bazar, Bhakurta, Savar, Dhaka-1313.'}</span>
                </li>
                <li className="flex items-center gap-4">
                  <Phone className="text-primary shrink-0" size={20} />
                  <span className="text-gray-300">{contact?.phone ?? '+8801987644603'}</span>
                </li>
                <li className="flex items-center gap-4">
                  <Mail className="text-primary shrink-0" size={20} />
                  <span className="text-gray-300">{contact?.email ?? 'contact@greenlandcompliance.com'}</span>
                </li>
              </ul>
            </div>

            <div className="flex gap-4 mt-10">
              {(contact?.social_links ?? []).map((link) => (
                <a
                  key={`${link.platform}-${link.url}`}
                  href={link.url}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#0b132b] cursor-pointer hover:bg-primary transition-colors"
                  title={socialTitle(link)}
                >
                  {socialIcon(link.platform)}
                </a>
              ))}
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-16">
          <div className="lg:col-span-2">
            <h2 className="text-3xl font-bold mb-2">Feedback form</h2>
            <div className="w-12 h-1.5 bg-primary mb-10"></div>

            <form className="space-y-6" onSubmit={handleSubmit}>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-6">
                  <input
                    type="text"
                    name="first_name"
                    placeholder="First name *"
                    value={form.first_name}
                    onChange={(event) => setForm((current) => ({ ...current, first_name: event.target.value }))}
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none"
                  />
                  {errors.first_name && <p className="text-sm text-red-600">{errors.first_name[0]}</p>}
                  <input
                    type="email"
                    name="email"
                    placeholder="E-mail *"
                    value={form.email}
                    onChange={(event) => setForm((current) => ({ ...current, email: event.target.value }))}
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none"
                  />
                  {errors.email && <p className="text-sm text-red-600">{errors.email[0]}</p>}
                  <input
                    type="tel"
                    name="phone"
                    placeholder="Phone"
                    value={form.phone}
                    onChange={(event) => setForm((current) => ({ ...current, phone: event.target.value }))}
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none"
                  />
                  {errors.phone && <p className="text-sm text-red-600">{errors.phone[0]}</p>}
                </div>
                <div>
                  <textarea
                    name="message"
                    placeholder="Your Message *"
                    rows={7}
                    value={form.message}
                    onChange={(event) => setForm((current) => ({ ...current, message: event.target.value }))}
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none resize-none h-full"
                  ></textarea>
                  {errors.message && <p className="text-sm text-red-600 mt-2">{errors.message[0]}</p>}
                </div>
              </div>

              {success && <p className="text-sm font-semibold text-green-700">{success}</p>}
              {errors.form && <p className="text-sm font-semibold text-red-600">{errors.form[0]}</p>}

              <button
                type="submit"
                disabled={submitting}
                className="bg-[#333] text-white py-4 px-10 rounded-sm font-bold flex items-center gap-4 hover:bg-black transition-colors disabled:opacity-60"
              >
                {submitting ? 'Submitting...' : 'Submit'} <ArrowRight size={18} />
              </button>
            </form>
          </div>

          <div>
            <h2 className="text-3xl font-bold mb-2">Your contact</h2>
            <div className="w-12 h-1.5 bg-primary mb-10"></div>

            <div className="space-y-10">
              {(contact?.departments ?? []).map((department) => (
                <div key={department.id} className="group">
                  <h3 className="text-xl font-bold mb-2">{department.title}</h3>
                  <p className="text-gray-500">
                    Email:{' '}
                    <a href={`mailto:${department.email}`} className="text-primary hover:underline">
                      {department.email}
                    </a>
                  </p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </main>
  );
}
