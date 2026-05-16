# Greenland Business & Compliance — REST API Specification

Project: Greenland Business & Compliance
Main frontend domain: `https://greenlandcompliance.com`
Base URL: provided by environment (`NEXT_PUBLIC_API_URL` in the frontend). Production example: `<BACKEND_URL>/api/v1`
Local dev: `http://localhost:8000/api/v1`
Document purpose: Complete reference for every REST API endpoint exposed by the Laravel backend. The Next.js frontend consumes these endpoints to render all dynamic content. This document defines request parameters, response shapes, HTTP status codes, and the standard response envelope.

---

## 1. Design Principles

This API follows a small set of consistent conventions so that the frontend developer can predict the shape of any endpoint without reading the full spec.

Every successful `GET` response returns HTTP 200 and wraps its payload in a `data` key. The successful contact form `POST` returns HTTP 201 and also wraps its payload in a `data` key. Errors return an appropriate 4xx or 5xx status and wrap the reason in an `error` key. Pagination metadata, when present, is returned alongside `data` in a `meta` key. These conventions are borrowed from JSON:API lite.

All timestamps are returned in ISO 8601 UTC format: `2026-05-13T10:30:00.000Z`.

All image/file URLs returned by the API are fully qualified absolute URLs based on the backend `APP_URL` (for example, `<BACKEND_URL>/storage/hero/hero1.jpg`). The frontend does not construct storage paths; it only uses what the API returns.

Endpoints that return ordered lists always respect the `sort_order` column and return items sorted ascending by it. The frontend should render items in the order the API returns them.

The API is stateless and requires no authentication for public read endpoints. The admin panel uses session-based authentication and is served through Laravel web routes, not these API routes. If the frontend ever needs to perform write operations (currently only the contact form submission), it posts to `POST /api/v1/contact` with no authentication.

---

## 2. Standard Response Envelope

### Successful single resource

```json
{
  "data": { ...resource fields... }
}
```

### Successful collection

```json
{
  "data": [ ...array of resources... ]
}
```

### Paginated collection

```json
{
  "data": [ ...items on this page... ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 20,
    "total": 48
  }
}
```

### Validation error (422)

```json
{
  "error": "Validation failed",
  "messages": {
    "email": ["The email field is required."],
    "message": ["The message field must not exceed 2000 characters."]
  }
}
```

### Generic server error (500)

```json
{
  "error": "An unexpected error occurred. Please try again."
}
```

### Resource not found (404)

```json
{
  "error": "Resource not found."
}
```

## 3. CORS Headers

The API returns CORS headers on every response, allowing the configured Next.js frontend origins to make requests from the browser. Local development must allow `http://localhost:3000`; production must allow the canonical frontend domain `https://greenlandcompliance.com` and `https://www.greenlandcompliance.com` if the `www` domain is enabled. Server-side `fetch` calls from Next.js Server Components are not subject to CORS but still work through these same endpoints.

```
Access-Control-Allow-Origin: <matching configured frontend origin>
Access-Control-Allow-Methods: GET, POST, OPTIONS
Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With
```

---

## 4. Endpoint Reference

---

### 4.1 Site — `GET /site`

Returns the global site settings that are consumed by the root layout, navbar, and footer of the Next.js app. This is the very first API call the frontend makes (cached at the layout level).

**Request:** No parameters.

**Response:**

```json
{
  "data": {
    "site_name": "Greenland Business & Compliance",
    "meta_title": "Greenland Business & Compliance",
    "meta_description": "Professional Advisory, Accounting, and Regulatory solutions in Bangladesh",
    "logo_url": "<BACKEND_URL>/storage/logo/gc.png",
    "primary_phone": "+8801987-644603",
    "primary_email": "contact@greenlandcompliance.com",
    "address": "Bottola Bazar, Bhakurta, Savar, Dhaka-1313, Bangladesh.",
    "business_hours": "Mon to Sat 8 am to 10 pm | Sunday CLOSED",
    "footer_description": "Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. Building sustainable business foundations in Bangladesh since 1985.",
    "footer_cta_title": "Ready to take your business to the next level?",
    "footer_cta_text": "Our expert consultants are ready to help you navigate the complexities of compliance and growth in Bangladesh.",
    "footer_cta_button_label": "Request a Free Quote",
    "footer_cta_button_href": "/contact",
    "copyright_text": "© 2026 Greenland Business & Compliance. All Rights Reserved.",
    "company_presentation_url": "<BACKEND_URL>/storage/resources/company-presentation.pdf",
    "how_we_work_video_url": "https://www.youtube.com/embed/dQw4w9WgXcQ",
    "map_embed_url": "https://www.google.com/maps/embed?pb=!1m18!...",
    "office_image_url": null,
    "social_links": [
      { "id": 1, "platform": "linkedin",  "url": "https://linkedin.com/company/greenland" },
      { "id": 2, "platform": "facebook",  "url": "https://facebook.com/greenland" },
      { "id": 3, "platform": "twitter",   "url": "https://twitter.com/greenland" },
      { "id": 4, "platform": "instagram", "url": "https://instagram.com/greenland" }
    ]
  }
}
```

**Implementation note:** The `logo_url`, `office_image_url`, and `company_presentation_url` fields are Eloquent accessors on the `SiteSetting` model. They call `asset('storage/' . $this->attribute_path)` and return `null` if the attribute is empty. Footer copy and footer CTA fields are global site settings because they are rendered on every page. The frontend must handle `null` gracefully for media/file fields.

---

### 4.2 Navigation — `GET /navigation`

Returns all navigation items grouped by location. The frontend uses this to render the header nav, the two footer link columns, and the policy link row in the footer bottom bar.

**Request:** No parameters.

**Response:**

```json
{
  "data": {
    "header": [
      { "id": 1, "label": "Home",         "href": "/" },
      { "id": 2, "label": "Services",     "href": "/services" },
      { "id": 3, "label": "Case Studies", "href": "/case-studies" },
      { "id": 4, "label": "About Us",     "href": "/about" },
      { "id": 5, "label": "Contact US",   "href": "/contact" },
      { "id": 6, "label": "Resources",    "href": "/resources" }
    ],
    "footer_quick": [
      { "id": 7,  "label": "Home",         "href": "/" },
      { "id": 8,  "label": "About Us",     "href": "/about" },
      { "id": 9,  "label": "Our Services", "href": "/services" },
      { "id": 10, "label": "Case Studies", "href": "/case-studies" },
      { "id": 11, "label": "Resources",    "href": "/resources" },
      { "id": 12, "label": "Contact Us",   "href": "/contact" }
    ],
    "footer_services": [
      { "id": 13, "label": "Business Advisory",         "href": "/services" },
      { "id": 14, "label": "Audit & Assurance",         "href": "/services" },
      { "id": 15, "label": "Taxation Services",         "href": "/services" },
      { "id": 16, "label": "Regulatory Compliance",     "href": "/services" },
      { "id": 17, "label": "Human Capital Management",  "href": "/services" },
      { "id": 18, "label": "Strategy Consulting",       "href": "/services" }
    ],
    "footer_policy": [
      { "id": 19, "label": "Privacy Policy",   "href": "/privacy" },
      { "id": 20, "label": "Terms of Service", "href": "/terms" },
      { "id": 21, "label": "Cookie Settings",  "href": "/cookies" }
    ]
  }
}
```

**Implementation note:** The `NavigationController` runs a single query (`NavItem::where('is_active', true)->orderBy('sort_order')->get()`) and groups the result by `location` in PHP memory. No SQL GROUP BY is needed.

---

### 4.3 Hero — `GET /hero`

Returns the hero slider settings: the headline copy, CTA buttons, and the ordered list of slide images. This is consumed by the `Hero.tsx` component.

**Request:** No parameters.

**Response:**

```json
{
  "data": {
    "headline_line1": "Your Vision, Our Compliance.",
    "headline_line2": "Building Sustainable Business Foundations in Bangladesh",
    "paragraph": "Professional Advisory, Accounting, and Regulatory solutions tailored for growth-minded entrepreneurs. We handle the complexity so you can lead with confidence.",
    "cta1_label": "Book a Consultation",
    "cta1_href": "/contact",
    "cta2_label": "Explore Our Services",
    "cta2_href": "/services",
    "slides": [
      {
        "id": 1,
        "image_url": "<BACKEND_URL>/storage/hero/hero1.jpg",
        "alt_text": "Hero slide 1",
        "sort_order": 1
      },
      {
        "id": 2,
        "image_url": "<BACKEND_URL>/storage/hero/hero2..jpg",
        "alt_text": "Hero slide 2",
        "sort_order": 2
      },
      {
        "id": 3,
        "image_url": "<BACKEND_URL>/storage/hero/hero3.jpg",
        "alt_text": "Hero slide 3",
        "sort_order": 3
      }
    ]
  }
}
```

**Implementation note:** The controller fetches the single `HeroSetting` row and merges it with active slides. If `slides` is an empty array (no active slides seeded), the frontend Hero component must degrade gracefully — showing a solid dark background with just the headline text.

---

### 4.4 Services — `GET /services`

Returns all service categories with their active services nested inside. The frontend uses this to populate the tab list and the service grid on the `/services` page.

**Request:** Optional query parameter `category` (slug string) filters to a single category. If omitted, all categories and their services are returned.

**Response (all categories):**

```json
{
  "data": [
    {
      "id": 1,
      "label": "Advisory",
      "slug": "advisory",
      "sort_order": 1,
      "services": [
        {
          "id": 1,
          "title": "Financial Services",
          "price": "$75",
          "description": "Companies dislike the term 'turnaround consulting' because it represents failure. The truth is that turnaround consulting represents success.",
          "badge": "NEW",
          "sort_order": 1
        },
        {
          "id": 2,
          "title": "Strategic planning",
          "price": "$60",
          "description": "Bonds and commodities are much more stable than stocks and trades. We allow our clients to invest in the right bonds & commodities.",
          "badge": null,
          "sort_order": 2
        }
      ]
    },
    {
      "id": 2,
      "label": "Audit",
      "slug": "audit",
      "sort_order": 2,
      "services": [
        {
          "id": 9,
          "title": "External Audit",
          "price": "$200",
          "description": "Comprehensive external auditing services for large corporations and SMEs to ensure regulatory compliance.",
          "badge": "NEW",
          "sort_order": 1
        }
      ]
    }
  ]
}
```

**Response (single category via `?category=advisory`):**

```json
{
  "data": {
    "id": 1,
    "label": "Advisory",
    "slug": "advisory",
    "sort_order": 1,
    "services": [ ...same service shape as above... ]
  }
}
```

---

**Routing rule:** `GET /services` is the canonical public endpoint. Use `?category=slug` for single-category filtering. Do not add or consume a separate `/services/category/{slug}` endpoint in the frontend unless this spec is deliberately versioned.

### 4.5 Case Studies — `GET /case-studies`

Returns all active case studies with their category. An optional `category` query parameter (category slug) filters results.

**Request:** Optional `?category=business-services`

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Healthcare giant overcomes merger in 2015",
      "slug": "healthcare-giant-overcomes-merger-2015",
      "image_url": "<BACKEND_URL>/storage/case-studies/healthcare.jpg",
      "summary": null,
      "sort_order": 1,
      "category": {
        "id": 1,
        "name": "Business Services",
        "slug": "business-services"
      }
    }
  ]
}
```

**Note on images:** When `image_url` is `null` (no image uploaded yet), the frontend renders the same `img here` placeholder it uses today, preserving visual parity. Once real images are uploaded through the admin panel, the frontend automatically displays them because it always checks for a non-null `image_url` before rendering the `<Image>` tag.

---

### 4.6 Case Study Categories — `GET /case-studies/categories`

Returns all case study categories. The current static `/case-studies` page does not render a category filter, so this endpoint is reserved for future filtering or admin previews. The initial dynamic migration may ignore it and render the case study grid exactly as it exists today.

**Response:**

```json
{
  "data": [
    { "id": 1, "name": "Business Services",             "slug": "business-services",              "sort_order": 1 },
    { "id": 2, "name": "Travel & Aviation",             "slug": "travel-aviation",                "sort_order": 2 },
    { "id": 3, "name": "Energy & Environment",          "slug": "energy-environment",             "sort_order": 3 },
    { "id": 4, "name": "Financial Services",            "slug": "financial-services",             "sort_order": 4 },
    { "id": 5, "name": "Surface Transport & Logistics", "slug": "surface-transport-logistics",    "sort_order": 5 },
    { "id": 6, "name": "Consumer Products",             "slug": "consumer-products",              "sort_order": 6 }
  ]
}
```

---

### 4.7 Single Case Study — `GET /case-studies/{slug}`

Returns full details for a single case study. Needed for a future case study detail page.

**Response:**

```json
{
  "data": {
    "id": 1,
    "title": "Healthcare giant overcomes merger in 2015",
    "slug": "healthcare-giant-overcomes-merger-2015",
    "image_url": "<BACKEND_URL>/storage/case-studies/healthcare.jpg",
    "summary": "A brief summary of the case study.",
    "body": "<p>Full HTML content of the case study...</p>",
    "sort_order": 1,
    "category": {
      "id": 1,
      "name": "Business Services",
      "slug": "business-services"
    }
  }
}
```

---

### 4.8 About — `GET /about`

Returns all content for the `/about` page in a single payload. Because the About page has six view tabs (overview, approach, achievement, partners, team, FAQ), all data is bundled into one response to avoid multiple round trips and waterfall loading. The frontend picks the relevant section from the payload based on the active tab.

**Response:**

```json
{
  "data": {
    "banner_label": "About Us",
    "company_presentation_url": "<BACKEND_URL>/storage/resources/company-presentation.pdf",

    "hero": {
      "heading_line1": "Workshops",
      "heading_line2": "that awesome!",
      "paragraph": "We are a company that offers design and build services for you from initial sketches to the final construction.",
      "image_url": "<BACKEND_URL>/storage/about/hero.png",
      "cta_label": "get a quote",
      "cta_href": "/contact"
    },

    "overview": {
      "paragraph1": "Greenland Business & Compliance is a leading advisory powerhouse in Bangladesh...",
      "paragraph2": "We achieved our success because of how successfully we integrate with our clients...",
      "callout": "Greenland continues to grow every day thanks to the confidence our clients have in us...",
      "mission_heading": "Our mission",
      "mission_intro": "Our renowned coaching programs will allow you to:",
      "mission_bullets": [
        { "id": 1, "text": "Work fewer hours — and make more money",                  "sort_order": 1 },
        { "id": 2, "text": "Attract and retain quality, high-paying customers",       "sort_order": 2 },
        { "id": 3, "text": "Manage your time so you'll get more done in less time",   "sort_order": 3 },
        { "id": 4, "text": "Hone sharp leadership skills to manage your team",        "sort_order": 4 },
        { "id": 5, "text": "Cut expenses without sacrificing quality",                "sort_order": 5 },
        { "id": 6, "text": "Automate your business so you can leave for weeks",       "sort_order": 6 }
      ],
      "how_we_work_video_url": "https://www.youtube.com/embed/dQw4w9WgXcQ",
      "timeline": [
        {
          "id": 1,
          "year": "1985",
          "title": "Start with a small service",
          "description": "This was the year when we started our company...",
          "sort_order": 1
        },
        {
          "id": 2,
          "year": "1990",
          "title": "First employees",
          "description": "This was the first period when Greenland actually felt like it would stick around...",
          "sort_order": 2
        },
        {
          "id": 3,
          "year": "2001",
          "title": "First recognition",
          "description": "By this time we were a well-known name within the industry...",
          "sort_order": 3
        },
        {
          "id": 4,
          "year": "2015",
          "title": "Greenland — corporation or family",
          "description": "Our journey has only brought us higher...",
          "sort_order": 4
        }
      ]
    },

    "approach": {
      "intro1": "Greenland Business & Compliance approaches every client's business as if it were our own...",
      "intro2": "The right approach is necessary for the right outcome...",
      "cards": [
        {
          "id": 1,
          "title": "Travel and Aviation Consulting",
          "icon": "Plane",
          "description": "Armed with statistical knowledge, technical expertise, and fact based prediction...",
          "sort_order": 1
        },
        {
          "id": 2,
          "title": "Business Services Consulting",
          "icon": "TrendingUp",
          "description": "We help you shape and position your business services...",
          "sort_order": 2
        },
        {
          "id": 3,
          "title": "Consumer Products Consulting",
          "icon": "ShoppingCart",
          "description": "We help companies dealing in consumer products...",
          "sort_order": 3
        },
        {
          "id": 4,
          "title": "Financial Services Consulting",
          "icon": "Building2",
          "description": "Our financial experts help you analyze financial data...",
          "sort_order": 4
        },
        {
          "id": 5,
          "title": "Energy and Environment Consulting",
          "icon": "Zap",
          "description": "We work with energy companies to increase their efficiency...",
          "sort_order": 5
        },
        {
          "id": 6,
          "title": "TAX Services Consulting",
          "icon": "Truck",
          "description": "We are a company that offers design and build services...",
          "sort_order": 6
        }
      ]
    },

    "achievements": [
      {
        "id": 1,
        "title": "Certificate of Achievement",
        "image_url": null,
        "sort_order": 1
      },
      {
        "id": 2,
        "title": "Certificate of Recognition",
        "image_url": null,
        "sort_order": 2
      },
      {
        "id": 3,
        "title": "Certificate of Excellence",
        "image_url": null,
        "sort_order": 3
      },
      {
        "id": 4,
        "title": "Certificate of Incorporation",
        "image_url": null,
        "sort_order": 4
      }
    ],

    "partners": [
      {
        "id": 1,
        "name": "Aramiz Company",
        "industry": "Athletic Performance Tracking Devices",
        "location": "Escondido, CA",
        "description": "We aren't such an agile and dependable organization just because of our own team...",
        "logo_url": null,
        "sort_order": 1
      },
      {
        "id": 2,
        "name": "Adup Media LLC",
        "industry": "Media & Marketing Consulting",
        "location": "Walnut Creek, CA",
        "description": "Our partners are the top companies in their own respective industries...",
        "logo_url": null,
        "sort_order": 2
      },
      {
        "id": 3,
        "name": "Green Shield",
        "industry": "Heart Transplant Monitoring Technology",
        "location": "Charlotte, NC",
        "description": "Strategic partnerships allow companies to expand and specialize without limitations...",
        "logo_url": null,
        "sort_order": 3
      },
      {
        "id": 4,
        "name": "Primo Software",
        "industry": "Software Development",
        "location": "Manitowoc, WI",
        "description": "Our customers trust us so much that they often come to us with problems beyond the scope...",
        "logo_url": null,
        "sort_order": 4
      }
    ],

    "team": [
      {
        "id": 1,
        "name": "Brandon Copperfield",
        "role": "Founder & CEO",
        "description": "The founder of Greenland Business & Compliance, he has been the captain of this ship...",
        "image_url": null,
        "profile_slug": null,
        "sort_order": 1
      },
      {
        "id": 2,
        "name": "Clark Roberts",
        "role": "Chief Finance Officer",
        "description": "Being the CFO in the Financial Industry is a tough task...",
        "image_url": null,
        "profile_slug": null,
        "sort_order": 2
      },
      {
        "id": 3,
        "name": "Ashley Hardy",
        "role": "VP Sales and Marketing",
        "description": "She is an accomplished business developer...",
        "image_url": null,
        "profile_slug": null,
        "sort_order": 3
      },
      {
        "id": 4,
        "name": "Dennis Norris",
        "role": "Chief Marketing Officer",
        "description": "He has helped Greenland reach new heights and enter new markets...",
        "image_url": null,
        "profile_slug": null,
        "sort_order": 4
      },
      {
        "id": 5,
        "name": "Gina Kennedy",
        "role": "Administrator",
        "description": "As we help other companies grow, she helps us grow...",
        "image_url": null,
        "profile_slug": null,
        "sort_order": 5
      },
      {
        "id": 6,
        "name": "Fernando Torres",
        "role": "Tax Consultant",
        "description": "Tax laws and regulations are some of the most complicated and infuriating parts...",
        "image_url": null,
        "profile_slug": null,
        "sort_order": 6
      }
    ],

    "faqs": [
      {
        "id": 1,
        "question": "How many times do I have to tell you a few ways?",
        "answer": "Progressively generate synergistic total linkage through cross-media intellectual capital...",
        "sort_order": 1
      },
      {
        "id": 2,
        "question": "What is do I have to tell you a few lorem?",
        "answer": "Greenland Business & Compliance continues to grow every day...",
        "sort_order": 2
      },
      {
        "id": 3,
        "question": "I have a technical problem I need resolved, who do I email?",
        "answer": "Please contact our technical support team at support@greenlandcompliance.com...",
        "sort_order": 3
      },
      {
        "id": 4,
        "question": "What other services are you compatible with?",
        "answer": "Our systems are designed to be highly compatible with modern enterprise software...",
        "sort_order": 4
      },
      {
        "id": 5,
        "question": "How many times do I have to tell you a few ways?",
        "answer": "This is another example of a frequently asked question...",
        "sort_order": 5
      },
      {
        "id": 6,
        "question": "What other services are you compatible with?",
        "answer": "We offer full integration services for a wide range of industry-standard tools.",
        "sort_order": 6
      }
    ],

    "testimonials": [
      {
        "id": 1,
        "author": "Damian Smulders",
        "role": "CEO, TechFlow",
        "quote": "The results were clear, professional, and persuasive, and the investors and advisors who have seen the materials loved them.",
        "avatar_url": "<BACKEND_URL>/storage/about/avatar1.png",
        "sort_order": 1
      },
      {
        "id": 2,
        "author": "Cintia Le Cane",
        "role": "Chairman, Harmony Corporation",
        "quote": "We thought a lot before choosing our compliance partner because we wanted to be sure our investment would yield results.",
        "avatar_url": "<BACKEND_URL>/storage/about/avatar2.png",
        "sort_order": 2
      },
      {
        "id": 3,
        "author": "Amanda Seyford",
        "role": "Founder & CEO, Arcade Systems",
        "quote": "We were amazed by how little effort was required on our part. An invaluable partner.",
        "avatar_url": "<BACKEND_URL>/storage/about/avatar3.png",
        "sort_order": 3
      }
    ],

    "footer_cta": {
      "text": "LOOKING FOR A FIRST-CLASS BUSINESS PLAN CONSULTANT?",
      "button_label": "get a quote",
      "button_href": "/contact"
    }
  }
}
```

**Implementation note:** The `AboutController::index()` method runs parallel queries for each sub-resource and combines them into this single shape. The overhead is low because the data volumes are small (fewer than 50 rows in total across all about tables). The frontend stores the result in a single `useState` call and derives tab content from it without re-fetching.

---

### 4.9 Contact Info — `GET /contact`

Returns all the static information needed to render the `/contact` page: address, phone, email, map URL, social links, and the department email directory.

**Response:**

```json
{
  "data": {
    "banner_label": "Our Office",
    "address": "Bottola Bazar, Bhakurta, Savar, Dhaka-1313.",
    "phone": "+8801987644603",
    "email": "contact@greenlandcompliance.com",
    "map_embed_url": "https://www.google.com/maps/embed?pb=!1m18!...",
    "office_image_url": null,
    "social_links": [
      { "platform": "linkedin",  "url": "https://linkedin.com/company/greenland" },
      { "platform": "facebook",  "url": "https://facebook.com/greenland" },
      { "platform": "instagram", "url": "https://instagram.com/greenland" },
      { "platform": "twitter",   "url": "https://twitter.com/greenland" },
      { "platform": "youtube",   "url": "https://youtube.com/@greenland" }
    ],
    "departments": [
      { "id": 1, "title": "Any Queries",     "email": "contact@greenlandcompliance.com", "sort_order": 1 },
      { "id": 2, "title": "Help or Support", "email": "help@greenlandcompliance.com",    "sort_order": 2 },
      { "id": 3, "title": "Job or Career",   "email": "career@greenlandcompliance.com",  "sort_order": 3 }
    ]
  }
}
```

---

### 4.10 Submit Contact Form — `POST /contact`

Accepts a form submission from the `/contact` page, validates it, stores the message, and returns a success response. No email is sent by default (the admin reads messages through the panel inbox), but this endpoint can easily be extended to dispatch a Laravel notification.

**Request body (JSON or form-data):**

```json
{
  "first_name": "Rahim",
  "email": "rahim@example.com",
  "phone": "+8801711000000",
  "message": "I would like to discuss regulatory compliance for my new business."
}
```

**Validation rules:**

The `first_name` field is required and must be a string of at most 100 characters. The `email` field is required and must be a valid email address. The `phone` field is optional and may be at most 30 characters. The `message` field is required and must be at most 2000 characters.

**Success response (201 Created):**

```json
{
  "data": {
    "message": "Your message has been received. We will get back to you shortly."
  }
}
```

**Validation error response (422):**

```json
{
  "error": "Validation failed",
  "messages": {
    "email": ["The email field must be a valid email address."],
    "message": ["The message field is required."]
  }
}
```

**Rate limiting:** This endpoint is rate-limited to 10 requests per IP per minute using Laravel's built-in throttle middleware to prevent spam.

---

### 4.11 Publications — `GET /resources/publications`

Returns all active publications for the Resources page Publications tab.

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Government Gazette on Labor Law 2023",
      "format": "pdf",
      "category": "Gazette",
      "file_url": null,
      "sort_order": 1
    },
    {
      "id": 2,
      "title": "Company Compliance Timeline 2024",
      "format": "jpg",
      "category": "Timeline",
      "file_url": null,
      "sort_order": 2
    },
    {
      "id": 3,
      "title": "Industrial Safety Guidelines",
      "format": "pdf",
      "category": "Nirdeshika",
      "file_url": null,
      "sort_order": 3
    },
    {
      "id": 4,
      "title": "Business Ethics & Conduct Book",
      "format": "pdf",
      "category": "Books",
      "file_url": null,
      "sort_order": 4
    },
    {
      "id": 5,
      "title": "Environmental Regulations Handbook",
      "format": "word",
      "category": "Manual",
      "file_url": null,
      "sort_order": 5
    },
    {
      "id": 6,
      "title": "Taxation Policy Update",
      "format": "pdf",
      "category": "Govt. Gazette",
      "file_url": null,
      "sort_order": 6
    }
  ]
}
```

**Note on `file_url`:** When a file has been uploaded via the admin panel, `file_url` returns the absolute storage URL (e.g. `<BACKEND_URL>/storage/resources/gazette-2023.pdf`). When no file has been uploaded yet, it returns `null`. The frontend renders the same visual download control, but as an intentionally disabled/unavailable visual state instead of a permanent fake `href="#"` link.

---

### 4.12 Forms & Templates — `GET /resources/forms`

Returns all active form templates grouped by `category_group`.

**Response:**

```json
{
  "data": {
    "Human Resources": [
      {
        "id": 1,
        "title": "Employee Onboarding Form",
        "format": "word",
        "language": "English",
        "file_url": null,
        "sort_order": 1
      },
      {
        "id": 2,
        "title": "Leave Application Template",
        "format": "excel",
        "language": "Bangla",
        "file_url": null,
        "sort_order": 2
      },
      {
        "id": 3,
        "title": "Performance Review Template",
        "format": "word",
        "language": "English",
        "file_url": null,
        "sort_order": 3
      }
    ],
    "Legal & Compliance": [
      {
        "id": 4,
        "title": "Trade License Renewal Form",
        "format": "word",
        "language": "Bangla",
        "file_url": null,
        "sort_order": 4
      },
      {
        "id": 5,
        "title": "Compliance Audit Checklist",
        "format": "excel",
        "language": "English",
        "file_url": null,
        "sort_order": 5
      },
      {
        "id": 6,
        "title": "Annual Return Template",
        "format": "excel",
        "language": "Bangla",
        "file_url": null,
        "sort_order": 6
      }
    ],
    "Finance & Accounts": [
      {
        "id": 7,
        "title": "Expense Claim Form",
        "format": "excel",
        "language": "English",
        "file_url": null,
        "sort_order": 7
      },
      {
        "id": 8,
        "title": "Tax Deduction Statement",
        "format": "excel",
        "language": "Bangla",
        "file_url": null,
        "sort_order": 8
      }
    ]
  }
}
```

**Implementation note:** The controller groups by `category_group` using a Laravel collection `groupBy('category_group')` call after fetching sorted items. The order within each group respects `sort_order`. The order of the groups themselves reflects the first item's `sort_order` across groups, so the admin controls both item order and group order by adjusting `sort_order` values.

---

### 4.13 News — `GET /resources/news`

Returns all active news posts for the Resources News tab, ordered newest first by default.

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Greenland Compliance joins Global Safety Summit",
      "category": "Events",
      "published_at": "2026-05-10",
      "image_url": null,
      "format": "jpg",
      "external_url": null,
      "sort_order": 1
    },
    {
      "id": 2,
      "title": "New Labor Law Amendments: What you need to know",
      "category": "Regulatory",
      "published_at": "2026-05-08",
      "image_url": null,
      "format": "png",
      "external_url": null,
      "sort_order": 2
    },
    {
      "id": 3,
      "title": "Annual General Meeting Highlights 2025",
      "category": "Corporate",
      "published_at": "2026-04-25",
      "image_url": null,
      "format": "jpg",
      "external_url": null,
      "sort_order": 3
    },
    {
      "id": 4,
      "title": "Excellence in Compliance Award Won",
      "category": "Awards",
      "published_at": "2026-04-20",
      "image_url": null,
      "format": "png",
      "external_url": null,
      "sort_order": 4
    }
  ]
}
```

**Note on news image display:** When `image_url` is null, the frontend displays the same `${format.toUpperCase()} Image` placeholder text it shows today. When a real image is uploaded through the admin panel and `image_url` becomes non-null, the frontend renders the `<Image>` component instead of the placeholder. This transition is seamless and requires no frontend code change because the frontend always checks `image_url` first.

---

### 4.14 CMS Page — `GET /pages/{slug}`

Returns the full HTML content of a CMS page. Used to render the missing `/privacy`, `/terms`, and `/cookies` routes.

**Path parameters:** `slug` must be one of `privacy`, `terms`, `cookies`.

**Response (200):**

```json
{
  "data": {
    "title": "Privacy Policy",
    "slug": "privacy",
    "content": "<h2>Introduction</h2><p>Your privacy is important to us...</p>"
  }
}
```

**Response (404) when slug not found:**

```json
{
  "error": "Resource not found."
}
```

---

### 4.15 Testimonials — `GET /testimonials`

Returns all active testimonials. Accepts an optional `page` query parameter (`about`, `case_studies`, `global`) to filter to a specific page context.

**Request:** Optional `?page=about`

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "author": "Damian Smulders",
      "role": "CEO, TechFlow",
      "quote": "The results were clear, professional, and persuasive...",
      "avatar_url": "<BACKEND_URL>/storage/about/avatar1.png",
      "sort_order": 1
    }
  ]
}
```

This endpoint is not consumed directly by the about page (testimonials are bundled in `GET /about`) but is available for future standalone testimonial sections.

---

## 5. HTTP Status Code Reference

This table summarises every HTTP status code this API can return and when to expect it.

`200 OK` is returned for all successful `GET` requests. `201 Created` is returned after a successful `POST /contact` submission. `422 Unprocessable Entity` is returned when request validation fails; the body contains field-level error messages. `404 Not Found` is returned when a slug or ID does not match any active resource. `405 Method Not Allowed` is returned when a client uses a verb not supported on an endpoint (e.g. `POST /api/v1/about`). `429 Too Many Requests` is returned when the contact form rate limit is exceeded; the `Retry-After` header indicates when the client may try again. `500 Internal Server Error` is returned for unhandled exceptions; the response body contains a generic message only (no stack traces in production).

---

## 6. Caching Strategy

All read endpoints return data that changes only when an admin updates content. The recommended caching approach is to use Next.js `fetch` with `{ next: { revalidate: 300 } }` (5-minute ISR revalidation) for all `GET` endpoints. The `/hero` endpoint should revalidate every 60 seconds since hero slides are high-visibility. The `/contact` endpoint and the form `POST` route must never be cached.

On the Laravel side, the `site`, `navigation`, and `about` endpoints may optionally be cached using `Cache::remember('key', now()->addMinutes(30), fn() => ...)` because their data rarely changes. The cache must be flushed whenever an admin saves changes in the panel; this can be done with a model observer that calls `Cache::forget('key')` on `saved` and `deleted` events.

---

## 7. Laravel API Controller Implementation Reference

### 7.1 SiteController

```php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\SocialLink;

class SiteController extends Controller
{
    public function index()
    {
        $settings     = SiteSetting::firstOrFail();
        $socialLinks  = SocialLink::where('is_active', true)->orderBy('sort_order')->get();

        return response()->json([
            'data' => [
                'site_name'                  => $settings->site_name,
                'meta_title'                 => $settings->meta_title,
                'meta_description'           => $settings->meta_description,
                'logo_url'                   => $settings->logo_url,
                'primary_phone'              => $settings->primary_phone,
                'primary_email'              => $settings->primary_email,
                'address'                    => $settings->address,
                'business_hours'             => $settings->business_hours,
                'footer_description'         => $settings->footer_description,
                'footer_cta_title'           => $settings->footer_cta_title,
                'footer_cta_text'            => $settings->footer_cta_text,
                'footer_cta_button_label'    => $settings->footer_cta_button_label,
                'footer_cta_button_href'     => $settings->footer_cta_button_href ?? '/contact',
                'copyright_text'             => $settings->copyright_text,
                'company_presentation_url'   => $settings->presentation_url,
                'how_we_work_video_url'      => $settings->how_we_work_video_url,
                'map_embed_url'              => $settings->map_embed_url,
                'office_image_url'           => $settings->office_image_url,
                'social_links'               => $socialLinks->map(fn($l) => [
                    'id'       => $l->id,
                    'platform' => $l->platform,
                    'url'      => $l->url,
                ]),
            ]
        ]);
    }
}
```

### 7.2 AboutController

```php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AboutSetting;
use App\Models\MissionBullet;
use App\Models\TimelineMilestone;
use App\Models\ApproachCard;
use App\Models\Achievement;
use App\Models\Partner;
use App\Models\TeamMember;
use App\Models\Faq;
use App\Models\SiteSetting;
use App\Models\Testimonial;

class AboutController extends Controller
{
    public function index()
    {
        $about         = AboutSetting::firstOrFail();
        $bullets       = MissionBullet::where('is_active', true)->orderBy('sort_order')->get();
        $timeline      = TimelineMilestone::orderBy('sort_order')->get();
        $approachCards = ApproachCard::where('is_active', true)->orderBy('sort_order')->get();
        $achievements  = Achievement::where('is_active', true)->orderBy('sort_order')->get();
        $partners      = Partner::where('is_active', true)->orderBy('sort_order')->get();
        $team          = TeamMember::where('is_active', true)->orderBy('sort_order')->get();
        $faqs          = Faq::where('is_active', true)->orderBy('sort_order')->get();
        $testimonials  = Testimonial::where('is_active', true)
                                    ->whereIn('page', ['about', 'global'])
                                    ->orderBy('sort_order')
                                    ->get();

        return response()->json([
            'data' => [
                'banner_label' => $about->banner_label,
                'company_presentation_url' => SiteSetting::first()?->presentation_url,

                'hero' => [
                    'heading_line1' => $about->hero_heading_line1,
                    'heading_line2' => $about->hero_heading_line2,
                    'paragraph'     => $about->hero_paragraph,
                    'image_url'     => $about->hero_image_path
                                        ? asset('storage/' . $about->hero_image_path)
                                        : null,
                    'cta_label'     => $about->hero_cta_label,
                    'cta_href'      => $about->hero_cta_href ?? '/contact',
                ],

                'overview' => [
                    'paragraph1'           => $about->overview_paragraph1,
                    'paragraph2'           => $about->overview_paragraph2,
                    'callout'              => $about->overview_callout,
                    'mission_heading'      => $about->mission_heading,
                    'mission_intro'        => $about->mission_intro,
                    'mission_bullets'      => $bullets->map(fn($b) => ['id' => $b->id, 'text' => $b->text, 'sort_order' => $b->sort_order]),
                    'how_we_work_video_url'=> SiteSetting::value('how_we_work_video_url'),
                    'timeline'             => $timeline->map(fn($t) => [
                        'id'          => $t->id,
                        'year'        => $t->year,
                        'title'       => $t->title,
                        'description' => $t->description,
                        'sort_order'  => $t->sort_order,
                    ]),
                ],

                'approach' => [
                    'intro1' => $about->approach_intro1,
                    'intro2' => $about->approach_intro2,
                    'cards'  => $approachCards->map(fn($c) => [
                        'id'          => $c->id,
                        'title'       => $c->title,
                        'icon'        => $c->icon,
                        'description' => $c->description,
                        'sort_order'  => $c->sort_order,
                    ]),
                ],

                'achievements' => $achievements->map(fn($a) => [
                    'id'         => $a->id,
                    'title'      => $a->title,
                    'image_url'  => $a->image_path ? asset('storage/' . $a->image_path) : null,
                    'sort_order' => $a->sort_order,
                ]),

                'partners' => $partners->map(fn($p) => [
                    'id'          => $p->id,
                    'name'        => $p->name,
                    'industry'    => $p->industry,
                    'location'    => $p->location,
                    'description' => $p->description,
                    'logo_url'    => $p->logo_path ? asset('storage/' . $p->logo_path) : null,
                    'sort_order'  => $p->sort_order,
                ]),

                'team' => $team->map(fn($m) => [
                    'id'           => $m->id,
                    'name'         => $m->name,
                    'role'         => $m->role,
                    'description'  => $m->description,
                    'image_url'    => $m->image_path ? asset('storage/' . $m->image_path) : null,
                    'profile_slug' => $m->profile_slug,
                    'sort_order'   => $m->sort_order,
                ]),

                'faqs' => $faqs->map(fn($f) => [
                    'id'         => $f->id,
                    'question'   => $f->question,
                    'answer'     => $f->answer,
                    'sort_order' => $f->sort_order,
                ]),

                'testimonials' => $testimonials->map(fn($t) => [
                    'id'         => $t->id,
                    'author'     => $t->author,
                    'role'       => $t->role,
                    'quote'      => $t->quote,
                    'avatar_url' => $t->avatar_path ? asset('storage/' . $t->avatar_path) : null,
                    'sort_order' => $t->sort_order,
                ]),

                'footer_cta' => [
                    'text'         => $about->footer_cta_text,
                    'button_label' => $about->footer_cta_button_label,
                    'button_href'  => $about->footer_cta_button_href ?? '/contact',
                ],
            ]
        ]);
    }
}
```

### 7.3 ContactController

```php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactDepartment;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // GET /api/v1/contact
    public function info()
    {
        $settings     = SiteSetting::firstOrFail();
        $departments  = ContactDepartment::where('is_active', true)->orderBy('sort_order')->get();
        $socialLinks  = SocialLink::where('is_active', true)->orderBy('sort_order')->get();

        return response()->json([
            'data' => [
                'banner_label'    => 'Our Office',
                'address'         => $settings->address,
                'phone'           => $settings->primary_phone,
                'email'           => $settings->primary_email,
                'map_embed_url'   => $settings->map_embed_url,
                'office_image_url'=> $settings->office_image_url,
                'social_links'    => $socialLinks->map(fn($l) => ['platform' => $l->platform, 'url' => $l->url]),
                'departments'     => $departments->map(fn($d) => [
                    'id'         => $d->id,
                    'title'      => $d->title,
                    'email'      => $d->email,
                    'sort_order' => $d->sort_order,
                ]),
            ]
        ]);
    }

    // POST /api/v1/contact
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'email'      => 'required|email|max:255',
            'phone'      => 'nullable|string|max:30',
            'message'    => 'required|string|max:2000',
        ]);

        ContactMessage::create($validated);

        return response()->json([
            'data' => ['message' => 'Your message has been received. We will get back to you shortly.']
        ], 201);
    }
}
```

---

## 8. API Versioning And Future Changes

All endpoints are prefixed with `/api/v1/`. If breaking changes are ever needed, a `/api/v2/` prefix is introduced for the changed endpoints only. The v1 endpoints continue working until the frontend has migrated. No endpoint in v1 will ever return fewer fields than documented here; new fields may be added at any time without a version bump since they are additive and will not break the existing frontend.
