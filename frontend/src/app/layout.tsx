import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import { Providers } from "@/components/providers";
import { AuthProvider } from "@/components/auth/auth-context";
import { Navbar } from "@/components/layout/navbar";
import { Footer } from "@/components/layout/footer";

const inter = Inter({ subsets: ["latin"], variable: "--font-inter" });

export const metadata: Metadata = {
  title: {
    default: "L9it — The Modern Moroccan Marketplace",
    template: "%s | L9it",
  },
  description:
    "L9it is the modern Moroccan classified marketplace. Buy, sell, and discover products across Morocco.",
  keywords: ["Morocco", "classifieds", "marketplace", "annonces", "vente"],
  authors: [{ name: "L9it" }],
  creator: "L9it",
  openGraph: {
    type: "website",
    locale: "fr_FR",
    siteName: "L9it",
  },
  robots: {
    index: true,
    follow: true,
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="fr" suppressHydrationWarning>
      <body className={`${inter.className} bg-white text-gray-900 antialiased`} suppressHydrationWarning>
        <AuthProvider>
          <Providers>
            <Navbar />
            <main className="min-h-screen">{children}</main>
            <Footer />
          </Providers>
        </AuthProvider>
      </body>
    </html>
  );
}
