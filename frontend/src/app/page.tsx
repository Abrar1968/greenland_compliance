import Hero from "@/components/Hero";
import { safeApiFetch } from "@/lib/api";
import type { HeroData } from "@/types/api";

export const dynamic = "force-dynamic";

export default async function Home() {
  const hero = await safeApiFetch<HeroData>("/hero", { cache: "no-store" });

  return (
    <main>
      <Hero data={hero} />
    </main>
  );
}
