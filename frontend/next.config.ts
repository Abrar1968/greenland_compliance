import type { NextConfig } from "next";
import path from "node:path";
import { fileURLToPath } from "node:url";

const projectRoot = path.dirname(fileURLToPath(import.meta.url));
const apiUrl = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000/api/v1";
const apiOrigin = new URL(apiUrl);

const nextConfig: NextConfig = {
  turbopack: {
    root: projectRoot,
  },
  images: {
    dangerouslyAllowLocalIP: ["localhost", "127.0.0.1"].includes(apiOrigin.hostname),
    remotePatterns: [
      {
        protocol: "http",
        hostname: "localhost",
        port: "8000",
        pathname: "/storage/**",
      },
      {
        protocol: "http",
        hostname: "127.0.0.1",
        port: "8000",
        pathname: "/storage/**",
      },
      {
        protocol: apiOrigin.protocol.replace(":", "") as "http" | "https",
        hostname: apiOrigin.hostname,
        port: apiOrigin.port,
        pathname: "/storage/**",
      },
    ],
  },
};

export default nextConfig;
