export interface ApiSingle<T> {
  data: T;
}

export interface ApiCollection<T> {
  data: T[];
}

export interface ApiGrouped<T> {
  data: Record<string, T[]>;
}

export interface SocialLink {
  id?: number;
  platform: string;
  url: string;
}

export interface SiteSettings {
  site_name: string;
  meta_title: string;
  meta_description: string;
  logo_url: string | null;
  primary_phone: string;
  primary_email: string;
  address: string;
  business_hours: string;
  footer_description: string;
  footer_cta_title: string;
  footer_cta_text: string;
  footer_cta_button_label: string;
  footer_cta_button_href: string;
  copyright_text: string;
  company_presentation_url: string | null;
  how_we_work_video_url: string | null;
  map_embed_url: string | null;
  office_image_url: string | null;
  social_links: SocialLink[];
}

export interface NavItem {
  id: number;
  label: string;
  href: string;
}

export interface Navigation {
  header: NavItem[];
  footer_quick: NavItem[];
  footer_services: NavItem[];
  footer_policy: NavItem[];
}

export interface HeroSlide {
  id: number;
  image_url: string;
  alt_text: string;
  sort_order: number;
}

export interface HeroData {
  headline_line1: string;
  headline_line2: string;
  paragraph: string;
  cta1_label: string;
  cta1_href: string;
  cta2_label: string;
  cta2_href: string;
  slides: HeroSlide[];
}

export interface ServiceItem {
  id: number;
  title: string;
  price: string;
  description: string;
  badge: "NEW" | "SPECIAL" | null;
  sort_order: number;
}

export interface ServiceCategory {
  id: number;
  label: string;
  slug: string;
  sort_order: number;
  services: ServiceItem[];
}

export interface CaseStudyCategory {
  id: number;
  name: string;
  slug: string;
  sort_order?: number;
}

export interface CaseStudy {
  id: number;
  title: string;
  slug: string;
  image_url: string | null;
  summary: string | null;
  sort_order: number;
  category: CaseStudyCategory;
}

export interface Testimonial {
  id: number;
  author: string;
  role: string;
  quote: string;
  avatar_url: string | null;
  sort_order: number;
}

export interface TimelineMilestone {
  id: number;
  year: string;
  title: string;
  description: string;
  sort_order: number;
}

export interface MissionBullet {
  id: number;
  text: string;
  sort_order: number;
}

export interface ApproachCard {
  id: number;
  title: string;
  icon: string;
  description: string;
  sort_order: number;
}

export interface Achievement {
  id: number;
  title: string;
  image_url: string | null;
  sort_order: number;
}

export interface Partner {
  id: number;
  name: string;
  industry: string | null;
  location: string | null;
  description: string | null;
  logo_url: string | null;
  sort_order: number;
}

export interface TeamMember {
  id: number;
  name: string;
  role: string;
  description: string | null;
  image_url: string | null;
  profile_slug: string | null;
  sort_order: number;
}

export interface Faq {
  id: number;
  question: string;
  answer: string;
  sort_order: number;
}

export interface AboutData {
  banner_label: string;
  company_presentation_url: string | null;
  hero: {
    heading_line1: string;
    heading_line2: string;
    paragraph: string;
    image_url: string | null;
    cta_label: string;
    cta_href: string;
  };
  overview: {
    paragraph1: string;
    paragraph2: string;
    callout: string;
    mission_heading: string;
    mission_intro: string;
    mission_bullets: MissionBullet[];
    how_we_work_video_url: string | null;
    timeline: TimelineMilestone[];
  };
  approach: {
    intro1: string;
    intro2: string;
    cards: ApproachCard[];
  };
  achievements: Achievement[];
  partners: Partner[];
  team: TeamMember[];
  faqs: Faq[];
  testimonials: Testimonial[];
  footer_cta: {
    text: string | null;
    button_label: string | null;
    button_href: string | null;
  };
}

export interface ContactDepartment {
  id: number;
  title: string;
  email: string;
  sort_order: number;
}

export interface ContactInfo {
  banner_label: string;
  address: string;
  phone: string;
  email: string;
  map_embed_url: string | null;
  office_image_url: string | null;
  social_links: SocialLink[];
  departments: ContactDepartment[];
}

export interface Publication {
  id: number;
  title: string;
  format: string;
  category: string;
  file_url: string | null;
  sort_order: number;
}

export interface FormTemplate {
  id: number;
  title: string;
  format: string;
  language: string;
  file_url: string | null;
  sort_order: number;
}

export interface NewsPost {
  id: number;
  title: string;
  category: string;
  published_at: string;
  image_url: string | null;
  format: string;
  external_url: string | null;
  sort_order: number;
}

export interface CmsPage {
  title: string;
  slug: string;
  content: string;
}

export interface ContactPayload {
  first_name: string;
  email: string;
  phone?: string;
  message: string;
}

export interface ValidationError {
  error: string;
  messages: Record<string, string[]>;
}
