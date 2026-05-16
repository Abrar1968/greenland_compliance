import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { safeApiFetch } from "@/lib/api";
import type { Navigation, SiteSettings } from "@/types/api";

const inter = Inter({
  variable: "--font-inter",
  subsets: ["latin"],
});

export async function generateMetadata(): Promise<Metadata> {
  const site = await safeApiFetch<SiteSettings>("/site", { revalidate: 300 });

  return {
    title: site?.meta_title ?? "Greenland Business & Compliance",
    description:
      site?.meta_description ??
      "Professional Advisory, Accounting, and Regulatory solutions in Bangladesh",
  };
}

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const [site, navigation] = await Promise.all([
    safeApiFetch<SiteSettings>("/site", { revalidate: 300 }),
    safeApiFetch<Navigation>("/navigation", { revalidate: 300 }),
  ]);

  return (
    <html lang="en" className={inter.variable} suppressHydrationWarning>
      <body>
        <Navbar site={site} navItems={navigation?.header ?? []} />
        {children}
        <Footer site={site} nav={navigation} />
      </body>
    </html>
  );
}
