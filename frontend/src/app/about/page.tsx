'use client';

import { useState } from 'react';
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
  Truck
} from 'lucide-react';

type View = 'overview' | 'approach' | 'achievement' | 'partners' | 'team' | 'faq';

export default function AboutPage() {
  const [activeView, setActiveView] = useState<View>('overview');

  const menuItems = [
    { id: 'overview', name: 'Company overview' },
    { id: 'approach', name: 'Our approach' },
    { id: 'achievement', name: 'Our Achievement' },
    { id: 'partners', name: 'Partners' },
    { id: 'team', name: 'Our Team' },
    { id: 'faq', name: 'FAQ' },
  ];

  return (
    <main className="pt-[120px] pb-20 bg-white font-sans">
      {/* Top Banner Section */}
      <div className="w-full h-[60px] mb-12">
        <div className="max-w-[1200px] mx-auto px-6 flex h-full">
          <div className="bg-[#333] text-primary px-10 flex items-center justify-center font-bold text-lg whitespace-nowrap">
            About Us
          </div>
          <div className="flex-1 bg-primary"></div>
        </div>
      </div>

      <div className="max-w-[1200px] mx-auto px-6">
        <div className="flex flex-col lg:flex-row gap-12">

          {/* Left Column - Dynamic Content */}
          <div className="lg:w-[72%]">

            {/* Hero Section - Always Visible */}
            <section className="bg-primary rounded-sm p-8 md:p-12 relative overflow-hidden flex flex-col md:flex-row items-center min-h-[350px] mb-12">
              <div className="md:w-1/2 z-10">
                <h1 className="text-4xl md:text-5xl font-extrabold text-secondary mb-4 leading-tight">
                  Workshops <br /> that awesome!
                </h1>
                <p className="text-secondary/80 mb-8 max-w-[350px] font-medium">
                  We are a company that offers design and build services for you from initial sketches to the final construction.
                </p>
                <button className="bg-secondary text-white px-6 py-3 rounded-sm flex items-center gap-2 hover:bg-secondary/90 transition-all font-bold">
                  get a quote <ChevronRight size={18} />
                </button>
              </div>
              <div className="md:w-1/2 relative h-[250px] md:h-[300px] w-full mt-8 md:mt-0">
                <Image
                  src="/about/hero.png"
                  alt="Laptop with dashboard"
                  fill
                  className="object-contain"
                />
              </div>
              {/* Dots */}
              <div className="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
                <div className="w-2.5 h-2.5 rounded-full bg-white opacity-50"></div>
                <div className="w-2.5 h-2.5 rounded-full bg-white"></div>
              </div>
            </section>

            {activeView === 'overview' && <OverviewView />}
            {activeView === 'approach' && <ApproachView />}
            {activeView === 'achievement' && <AchievementView />}
            {activeView === 'partners' && <PartnersView />}
            {activeView === 'team' && <TeamView />}
            {activeView === 'faq' && <FAQView />}
          </div>

          {/* Right Column - Sidebar */}
          <aside className="lg:w-[28%] space-y-8">

            {/* Side Menu */}
            <nav className="bg-gray-50 border border-gray-100">
              <ul className="divide-y divide-gray-200">
                {menuItems.map((item) => (
                  <li key={item.id}>
                    <button
                      onClick={() => setActiveView(item.id as View)}
                      className={`w-full text-left px-6 py-4 font-bold text-sm transition-colors flex justify-between items-center ${activeView === item.id ? 'bg-white text-primary border-l-4 border-primary shadow-sm' : 'text-gray-700 hover:bg-white hover:text-primary'}`}
                    >
                      {item.name}
                      <ChevronRight size={16} className={activeView === item.id ? 'text-primary' : 'text-gray-400'} />
                    </button>
                  </li>
                ))}
              </ul>
            </nav>

            {/* Company Presentation */}
            <button className="w-full bg-secondary text-white p-6 rounded-sm flex items-center gap-4 hover:bg-secondary/95 transition-all group text-left">
              <div className="bg-gray-700 p-3 rounded-sm group-hover:bg-primary transition-colors shrink-0">
                <Play size={20} className="fill-white text-white" />
              </div>
              <div>
                <span className="block text-xs uppercase opacity-70 tracking-wider">Download</span>
                <span className="block font-bold text-lg leading-tight">Company presentation</span>
              </div>
            </button>

            {/* Help Box */}
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

            {/* Testimonials */}
            <div className="space-y-8">
              <Testimonial
                quote="The results were clear, professional, and persuasive, and the investors and advisors who have seen the materials loved them. They know what investors want."
                author="Damian Smulders"
                role="CEO, TechFlow"
                avatar="/about/avatar1.png"
              />
              <Testimonial
                quote="We thought a lot before choosing the Financial WordPress Theme because we wanted to sure our investment would yield results. Consulting theme is an invaluable partner."
                author="Cintia Le Cane"
                role="Chairman, Harmony Corporation"
                avatar="/about/avatar2.png"
              />
              <Testimonial
                quote="We were amazed by how little effort was required on our part to have Consulting WP prepare these materials. We exchanged a few phone calls. Consulting theme is an invaluable partner."
                author="Amanda Seyford"
                role="Founder & CEO, Arcade Systems"
                avatar="/about/avatar3.png"
              />
            </div>

          </aside>

        </div>

        {/* Footer CTA Section - Full Width of Container */}
        <section className="mt-20 bg-primary p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6 rounded-sm">
          <h3 className="text-white text-xl md:text-2xl font-bold text-center md:text-left">
            LOOKING FOR A FIRST-CLASS BUSINESS PLAN CONSULTANT?
          </h3>
          <button className="bg-secondary text-white px-8 py-3 rounded-sm flex items-center gap-2 hover:bg-secondary/90 transition-all font-bold whitespace-nowrap">
            get a quote <ChevronRight size={18} />
          </button>
        </section>
      </div>
    </main>
  );
}

function OverviewView() {
  return (
    <>
      {/* Timeline Section */}
      <section className="py-16">
        <div className="space-y-12">
          <TimelineItem
            year="1985"
            title="Start with a small service"
            description="This was the year when we started our company. We had no idea how far we would go, we weren't even sure that we would be able to survive for a few years. What drove us to start the company was the understanding that we could provide a service no one else was providing."
          />
          <TimelineItem
            year="1990"
            title="First employees"
            description="This was the first period when Consulting WP actually felt like it would stick around for a while. We realized we were growing more stable and expanding in the same area. We needed a new office as we had severely outgrown the last one. We started searching for a new location."
          />
          <TimelineItem
            year="2001"
            title="First recognition"
            description="By this time we were a well-known name within the industry. We had been prominent members of the industry for more than 16 years, and worked for some of the biggest clients in the industry, we weren't dismissed by anyone because we could not be dismissed by anyone."
          />
          <TimelineItem
            year="2015"
            title="Consulting wp — corporation or family"
            description="Our journey has only brought us higher. Information Technology completely changes the way we analyze and present data. We have embraced new technologies and have ensured that our clients receive cutting edge analytics. As we go towards the future we intend to exploit the full potential of new technologies to power our services."
          />
        </div>
      </section>

      {/* Company Overview Section */}
      <section className="py-8">
        <h2 className="text-3xl font-bold uppercase text-secondary mb-8 relative inline-block">
          COMPANY OVERVIEW
          <div className="absolute -bottom-2 left-0 w-12 h-1 bg-primary"></div>
        </h2>

        <div className="space-y-6 text-gray-600 leading-relaxed">
          <p>
            Consulting WP is a global consulting powerhouse. We began our operations a few decades ago and have grown due to excellent relationships with our clients. We started out small, with just a few people and a small office, but today we have offices in multiple countries with hundreds of people working inside them.
          </p>
          <p>
            We achieved our success because of how successfully we integrate with our clients. One complaint many people have about consultants is that they can be disruptive. Employees fear outside consultants coming in and destroying the workflow. Our clients face no such issues.
          </p>

          <div className="border-l-4 border-primary pl-6 py-4 my-8 bg-gray-50 italic text-secondary font-medium">
            Consulting WP continues to grow every day thanks to the confidence our clients have in us. We cover many industries such as financial, energy, business services, consumer products.
          </div>
        </div>

        {/* Mission & How we work */}
        <div className="grid md:grid-cols-2 gap-12 mt-12">
          <div>
            <h3 className="text-2xl font-bold text-secondary mb-6">Our mission</h3>
            <p className="text-gray-600 mb-6">Our renowned coaching programs will allow you to:</p>
            <ul className="space-y-3">
              {[
                'Work fewer hours — and make more money',
                'Attract and retain quality, high-paying customers',
                'Manage your time so you\'ll get more done in less time',
                'Hone sharp leadership skills to manage your team',
                'Cut expenses without sacrificing quality',
                'Automate your business, so you can leave for days, weeks, or even months at a time'
              ].map((item, idx) => (
                <li key={idx} className="flex items-start gap-2 text-gray-600 text-sm">
                  <span className="text-primary font-bold mt-[-2px]">•</span>
                  {item}
                </li>
              ))}
            </ul>
          </div>
          <div>
            <h3 className="text-2xl font-bold text-secondary mb-6">How we work</h3>
            <div className="aspect-video w-full rounded-sm overflow-hidden shadow-lg bg-black">
              <iframe
                width="100%"
                height="100%"
                src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                title="YouTube video player"
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowFullScreen
              ></iframe>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

function ApproachView() {
  const approachItems = [
    {
      title: 'Travel and Aviation Consulting',
      desc: 'Armed with statistical knowledge, technical expertise, and fact based prediction, we allow your business to truly soar.',
      icon: Plane
    },
    {
      title: 'Business Services Consulting',
      desc: 'We help you shape and position your business services in a way that enhances the output of your clients.',
      icon: TrendingUp
    },
    {
      title: 'Consumer Products Consulting',
      desc: 'We help companies dealing in consumer products create and present products that perfectly blend in with the zeitgeist.',
      icon: ShoppingCart
    },
    {
      title: 'Financial Services Consulting',
      desc: 'Our financial experts help you analyze financial data, to create a rock steady financial foundation.',
      icon: Building2
    },
    {
      title: 'Energy and Environment Consulting',
      desc: 'We work with energy companies to increase their efficiency and eliminate any environmentally harmful practices.',
      icon: Zap
    },
    {
      title: 'TAX Services Consulting',
      desc: 'We are a company that offers design and build services for you from initial sketches to the final construction.',
      icon: Truck
    }
  ];

  return (
    <div className="animate-fadeInUp">
      <h2 className="text-4xl font-extrabold uppercase text-secondary mb-8 relative inline-block">
        OUR APPROACH
        <div className="absolute -bottom-3 left-0 w-16 h-1.5 bg-primary"></div>
      </h2>

      <div className="space-y-6 text-gray-600 leading-relaxed mb-16 text-lg">
        <p>
          Consulting WP approaches every client&apos;s business as if it were our own. We believe a consulting firm should be more than an advisor. We put ourselves in our clients&apos; shoes, align our incentives with their objectives, and collaborate to unlock the full potential of their business. This builds deep and enjoyable relationships.
        </p>
        <p>
          The right approach is necessary for the right outcome. Consulting WP approaches work by applying its external knowledge to your organization&apos;s internal way of doing work. We know that in order to maximize the potential of success for your company we need to shape our expert advice in a way that applies to your way of doing business. This allows us to create rich relationships with our clients.
        </p>
      </div>

      <div className="grid md:grid-cols-2 gap-y-16 gap-x-12">
        {approachItems.map((item, idx) => (
          <div key={idx} className="flex gap-6 group">
            <div className="shrink-0 relative">
              {/* Hexagon shape using clip-path */}
              <div
                className="w-16 h-16 bg-white border-2 border-secondary flex items-center justify-center group-hover:bg-primary group-hover:border-primary transition-all duration-300"
                style={{ clipPath: 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)' }}
              >
                <item.icon size={28} className="text-secondary group-hover:text-white transition-colors" />
              </div>
            </div>
            <div>
              <h4 className="text-xl font-bold text-secondary mb-3 group-hover:text-primary transition-colors leading-tight">
                {item.title}
              </h4>
              <p className="text-gray-500 text-[15px] leading-relaxed">
                {item.desc}
              </p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function AchievementView() {
  const certificates = [
    { title: 'Certificate of Achievement', img: '/about/cert1.png' },
    { title: 'Certificate of Recognition', img: '/about/cert2.png' },
    { title: 'Certificate of Excellence', img: '/about/cert3.png' },
    { title: 'Certificate of Incorporation', img: '/about/cert4.png' },
  ];

  return (
    <div className="animate-fadeInUp">
      <h2 className="text-4xl font-extrabold uppercase text-secondary mb-12 relative inline-block">
        OUR ACHIEVEMENT
        <div className="absolute -bottom-3 left-0 w-16 h-1.5 bg-primary"></div>
      </h2>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-12">
        {certificates.map((cert, idx) => (
          <div key={idx} className="group">
            <div className="mb-6">
              <h4 className="text-xl font-bold text-secondary mb-2 flex items-center gap-2 group-hover:text-primary transition-colors">
                {cert.title}
              </h4>
              <div className="w-10 h-1 bg-primary group-hover:w-16 transition-all duration-300"></div>
            </div>

            <div className="aspect-[4/3] bg-gray-50 border border-gray-200 rounded-sm overflow-hidden relative shadow-sm group-hover:shadow-md transition-shadow">
              {cert.img ? (
                <Image
                  src={cert.img}
                  alt={cert.title}
                  fill
                  className="object-cover group-hover:scale-105 transition-transform duration-500"
                />
              ) : (
                <div className="absolute inset-0 flex items-center justify-center">
                  <span className="text-gray-300 font-medium italic">Certificate Image</span>
                </div>
              )}

              {/* Subtle Overlay on Hover */}
              <div className="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function PartnersView() {
  const partners = [
    {
      name: 'Aramiz Company',
      industry: 'Athletic Performance Tracking Devices',
      location: 'Escondido, CA',
      desc: 'We aren\'t such an agile and dependable organization just because of our own team; we also have a fantastic network of partners who compliment our services and allow us to work harder, better, faster, and stronger.'
    },
    {
      name: 'Adup Media LLC',
      industry: 'Media & Marketing Consulting',
      location: 'Walnut Creek, CA',
      desc: 'Our partners are the top companies in their own respective industries, and are known to deliver high quality services and products. Our partners help us deliver unparalleled services to our clients. The reality is that no company can survive by its own in the age of information technology.'
    },
    {
      name: 'Green Shield',
      industry: 'Heart Transplant Monitoring Technology',
      location: 'Charlotte, NC',
      desc: 'Strategic partnerships allow companies to expand and specialize without limitations. Instead of spending a lot of money and time perfecting a new thing, we prefer to perfect our own services and call in the experts for other tasks when needed. We are the best financial consultants for our clients.'
    },
    {
      name: 'Primo Software',
      industry: 'Software Development',
      location: 'Manitowoc, WI',
      desc: 'Our customers trust us so much that they often come to us with problems beyond the scope of financial consultancy. Since they know that we have their best business interests at heart they want us to help them with such problems.'
    }
  ];

  return (
    <div className="animate-fadeInUp space-y-16">
      {partners.map((partner, idx) => (
        <div key={idx} className="group">
          <div className="mb-6">
            <h3 className="text-3xl font-bold text-secondary mb-1 group-hover:text-primary transition-colors">
              {partner.name}
            </h3>
            <div className="flex flex-col text-gray-400 text-sm font-medium">
              <span>{partner.industry}</span>
              <span>{partner.location}</span>
            </div>
          </div>
          <div className="w-full h-px bg-gray-100 mb-8"></div>
          <p className="text-gray-500 leading-relaxed text-lg">
            {partner.desc}
          </p>
        </div>
      ))}
    </div>
  );
}

function TeamView() {
  const team = [
    { name: 'Brandon Copperfield', role: 'Founder & CEO', desc: 'The founder of Consulting WP, he has been the captain of this ship from the beginning and has sailed...' },
    { name: 'Clark Roberts', role: 'Chief Finance Officer', desc: 'Being the CFO in the Financial Industry is a tough task, thankfully he was here to man the helm...' },
    { name: 'Ashley Hardy', role: 'VP Sales and Marketing', desc: 'She is an accomplished business developer. Her skills at creating relationships with clients are...' },
    { name: 'Dennis Norris', role: 'Chief Marketing Officer', desc: 'He has helped Business WordPress Theme reach new heights and enter new markets. His skills of understanding...' },
    { name: 'Gina Kennedy', role: 'Administrator', desc: 'As we help other companies grow, she helps us grow. She handles all the internal work at WP consulting...' },
    { name: 'Fernando Torres', role: 'Tax Consultant', desc: 'Tax laws and regulations are some of the most complicated and infuriating parts of the financial...' },
  ];

  return (
    <div className="animate-fadeInUp">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-16">
        {team.map((member, idx) => (
          <div key={idx} className="flex gap-6 group">
            <div className="shrink-0">
              <div className="w-32 h-32 rounded-full border-2 border-gray-100 bg-gray-50 flex items-center justify-center overflow-hidden group-hover:border-primary transition-colors relative">
                <div className="text-gray-300 italic text-xs">Image Frame</div>
                {/* Image placeholder - user will add later */}
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
                {member.desc}
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

function FAQView() {
  const [openIdx, setOpenIdx] = useState<number | null>(0);

  const faqs = [
    { q: 'How many times do I have to tell you a few ways?', a: 'Progressively generate synergistic total linkage through cross-media intellectual capital. Enthusiastically parallel task team building e-tailers without standards compliant initiatives.' },
    { q: 'What is do I have to tell you a few lorem?', a: 'Consulting WP continues to grow every day thanks to the confidence our clients have in us. We cover many industries such as financial, energy, business services, consumer products.' },
    { q: 'I have a technical problem I need resolved, who do I email?', a: 'Please contact our technical support team at support@greenlandcompliance.com for any technical inquiries or issues.' },
    { q: 'What other services are you compatible with?', a: 'Our systems are designed to be highly compatible with modern enterprise software including SAP, Oracle, and Microsoft Dynamics.' },
    { q: 'How many times do I have to tell you a few ways?', a: 'This is another example of a frequently asked question to demonstrate the accordion functionality.' },
    { q: 'What other services are you compatible with?', a: 'We offer full integration services for a wide range of industry-standard tools.' },
  ];

  return (
    <div className="animate-fadeInUp">
      <h2 className="text-4xl font-extrabold uppercase text-secondary mb-8 relative inline-block">
        FAQ
        <div className="absolute -bottom-3 left-0 w-16 h-1.5 bg-primary"></div>
      </h2>

      <div className="space-y-4">
        {faqs.map((faq, idx) => (
          <div key={idx} className="border border-gray-100 rounded-sm overflow-hidden">
            <button
              onClick={() => setOpenIdx(openIdx === idx ? null : idx)}
              className="w-full flex items-center gap-4 px-6 py-5 text-left bg-white hover:bg-gray-50 transition-colors group"
            >
              <div className="shrink-0 w-5 h-5 flex items-center justify-center font-bold text-lg text-secondary group-hover:text-primary">
                {openIdx === idx ? '−' : '+'}
              </div>
              <span className="font-bold text-secondary text-lg group-hover:text-primary transition-colors">
                {faq.q}
              </span>
            </button>

            <div
              className={`grid transition-all duration-300 ease-in-out ${openIdx === idx ? 'grid-rows-[1fr] opacity-100 py-6' : 'grid-rows-[0fr] opacity-0'}`}
            >
              <div className="overflow-hidden px-14">
                <p className="text-gray-500 leading-relaxed">
                  {faq.a}
                </p>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

function TimelineItem({ year, title, description }: { year: string, title: string, description: string }) {
  return (
    <div className="flex gap-8 relative">
      <div className="text-2xl font-bold text-secondary w-16 pt-1 shrink-0">{year}</div>
      <div className="relative flex flex-col items-center">
        <div className="w-3 h-3 rounded-full bg-primary mt-3 z-10"></div>
        <div className="absolute top-4 bottom-[-48px] w-[2px] bg-gray-200"></div>
      </div>
      <div className="flex-1 pb-12">
        <h4 className="text-xl font-bold text-secondary mb-3">{title}</h4>
        <p className="text-gray-500 text-sm leading-relaxed">{description}</p>
      </div>
    </div>
  );
}

function Testimonial({ quote, author, role, avatar }: { quote: string, author: string, role: string, avatar: string }) {
  return (
    <div className="bg-white border border-gray-100 p-6 shadow-sm rounded-sm relative">
      <div className="mb-4">
        <p className="text-gray-500 text-sm italic leading-relaxed">
          &quot;{quote}&quot;
        </p>
      </div>
      <div className="absolute -bottom-2 right-4 text-primary opacity-20">
        <Quote size={40} fill="currentColor" />
      </div>
      <div className="flex items-center gap-3 mt-4">
        <div className="w-12 h-12 rounded-full overflow-hidden border border-gray-200 shrink-0">
          <Image src={avatar} alt={author} width={48} height={48} className="object-cover" />
        </div>
        <div>
          <h5 className="font-bold text-secondary text-sm">{author}</h5>
          <p className="text-xs text-gray-400">{role}</p>
        </div>
      </div>
    </div>
  );
}


