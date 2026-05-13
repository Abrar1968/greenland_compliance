import { MapPin, Phone, Mail, Globe, MessageSquare, ArrowRight, Share2, Users, Camera } from 'lucide-react';

export default function ContactPage() {
  return (
    <main className="min-h-screen pt-[120px] pb-20 bg-white font-sans">
      {/* Top Banner Section */}
      <div className="w-full h-[60px] mb-12">
        <div className="max-w-[1200px] mx-auto px-6 flex h-full">
          <div className="bg-[#333] text-primary px-10 flex items-center justify-center font-bold text-lg whitespace-nowrap">
            Our Office
          </div>
          <div className="flex-1 bg-primary"></div>
        </div>
      </div>

      <div className="max-w-[1200px] mx-auto px-6">
        {/* Top Grid: Image, Map, Contact Info */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
          {/* 1. Empty Image Placeholder */}
          <div className="bg-gray-100 border-2 border-dashed border-gray-300 rounded-sm h-[350px] flex items-center justify-center relative overflow-hidden group">
            <span className="text-gray-400 font-medium">Image Placeholder</span>
            {/* You can add <Image src="..." fill /> here later */}
          </div>

          {/* 2. Google Map */}
          <div className="h-[350px] w-full rounded-sm overflow-hidden border border-gray-200">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d116834.00977782334!2d90.2828269!3d23.7664474!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755eab599be42f1%3A0x82f910ca15f9df45!2sBottola%20Bazar!5e0!3m2!1sen!2sbd!4v1714392000000!5m2!1sen!2sbd"
              width="100%"
              height="100%"
              style={{ border: 0 }}
              allowFullScreen
              loading="lazy"
              referrerPolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>

          {/* 3. Contact Details Box */}
          <div className="bg-[#0b132b] text-white p-10 rounded-sm flex flex-col justify-between">
            <div>
              <h2 className="text-2xl font-bold mb-8">Contact Details</h2>
              <ul className="space-y-6">
                <li className="flex items-start gap-4">
                  <MapPin className="text-primary mt-1 shrink-0" size={20} />
                  <span className="text-gray-300">Bottola Bazar, Bhakurta, Savar, Dhaka-1313.</span>
                </li>
                <li className="flex items-center gap-4">
                  <Phone className="text-primary shrink-0" size={20} />
                  <span className="text-gray-300">+8801987644603</span>
                </li>
                <li className="flex items-center gap-4">
                  <Mail className="text-primary shrink-0" size={20} />
                  <span className="text-gray-300">contact@greenlandcompliance.com</span>
                </li>
              </ul>
            </div>

            {/* Social Icons */}
            <div className="flex gap-4 mt-10">
              <div className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#0b132b] cursor-pointer hover:bg-primary transition-colors">
                <Share2 size={18} />
              </div>
              <div className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#0b132b] cursor-pointer hover:bg-primary transition-colors">
                <Users size={18} />
              </div>
              <div className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#0b132b] cursor-pointer hover:bg-primary transition-colors">
                <Camera size={18} />
              </div>
              <div className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#0b132b] cursor-pointer hover:bg-primary transition-colors">
                <Globe size={18} />
              </div>
              <div className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#0b132b] cursor-pointer hover:bg-primary transition-colors">
                <MessageSquare size={18} />
              </div>
            </div>
          </div>
        </div>

        {/* Bottom Section: Form + Info */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-16">
          {/* Feedback Form (2/3 width on desktop) */}
          <div className="lg:col-span-2">
            <h2 className="text-3xl font-bold mb-2">Feedback form</h2>
            <div className="w-12 h-1.5 bg-primary mb-10"></div>
            
            <form className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-6">
                  <input 
                    type="text" 
                    placeholder="First name *" 
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none"
                  />
                  <input 
                    type="email" 
                    placeholder="E-mail *" 
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none"
                  />
                  <input 
                    type="tel" 
                    placeholder="Phone *" 
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none"
                  />
                </div>
                <div>
                  <textarea 
                    placeholder="Your Message *" 
                    rows={7}
                    className="w-full bg-gray-50 border-none p-4 rounded-sm focus:ring-1 focus:ring-primary outline-none resize-none h-full"
                  ></textarea>
                </div>
              </div>
              
              <button 
                type="submit" 
                className="bg-[#333] text-white py-4 px-10 rounded-sm font-bold flex items-center gap-4 hover:bg-black transition-colors"
              >
                Submit <ArrowRight size={18} />
              </button>
            </form>
          </div>

          {/* Sidebar Info (1/3 width) */}
          <div>
            <h2 className="text-3xl font-bold mb-2">Your contact</h2>
            <div className="w-12 h-1.5 bg-primary mb-10"></div>

            <div className="space-y-10">
              <div className="group">
                <h3 className="text-xl font-bold mb-2">Any Quires</h3>
                <p className="text-gray-500">Email: <a href="mailto:contact@greenlandcompliance.com" className="text-primary hover:underline">contact@greenlandcompliance.com</a></p>
              </div>

              <div className="group">
                <h3 className="text-xl font-bold mb-2">Help or Support</h3>
                <p className="text-gray-500">Email: <a href="mailto:help@greenlandcompliance.com" className="text-primary hover:underline">help@greenlandcompliance.com</a></p>
              </div>

              <div className="group">
                <h3 className="text-xl font-bold mb-2">Job or Career</h3>
                <p className="text-gray-500">Email: <a href="mailto:career@greenlandcompliance.com" className="text-primary hover:underline">career@greenlandcompliance.com</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  );
}
