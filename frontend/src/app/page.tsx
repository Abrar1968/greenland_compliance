import Hero from "@/components/Hero";
import { safeApiFetch } from "@/lib/api";
import type { HeroData } from "@/types/api";

export default async function Home() {
  const hero = await safeApiFetch<HeroData>("/hero", { revalidate: 60 });

  return (
    <main>
      <Hero data={hero} />
    </main>
  );
}
