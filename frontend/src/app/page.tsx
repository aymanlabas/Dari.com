"use client";

import Image from "next/image";
import { useState } from "react";
import { Search, MapPin, SlidersHorizontal, ArrowRight } from "lucide-react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { CATEGORIES } from "@/lib/utils";
import { useListings } from "@/lib/api/queries";

export default function Home() {
  const router = useRouter();
  const [searchQuery, setSearchQuery] = useState("");
  const { data: featuredListings, isLoading } = useListings({ sort: "views", page: 1 });
  const { data: recentListings } = useListings({ sort: "recent", page: 1 });

  return (
    <div>
      {/* Hero Section */}
      <section className="relative overflow-hidden bg-gradient-to-br from-green-50 via-white to-brand-50 py-16 lg:py-28">
        <div className="container mx-auto px-4">
          <div className="max-w-3xl mx-auto text-center">
            <h1 className="text-4xl lg:text-6xl font-extrabold text-gray-900 tracking-tight mb-4">
              L9it li kat9elleb{" "}
              <span className="text-green-600">3lih</span>
            </h1>
            <p className="text-lg lg:text-xl text-gray-600 mb-10">
              Découvrez, achetez et vendez facilement à travers tout le Maroc.
            </p>

            {/* Search Bar */}
            <div className="bg-white rounded-2xl shadow-lg p-2 flex flex-col sm:flex-row gap-2">
              <div className="flex-1 flex items-center gap-2 px-4">
                <Search className="w-5 h-5 text-gray-400" />
                <input
                  type="text"
                  placeholder="Qu'est-ce que vous recherchez ?"
                  className="w-full py-3 outline-none text-gray-900 placeholder-gray-400"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>
              <button
                onClick={() => router.push(`/annonces?keyword=${searchQuery}`)}
                className="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors whitespace-nowrap"
              >
                Rechercher
              </button>
            </div>

            <div className="flex flex-wrap justify-center gap-4 mt-8 text-sm">
              {["Véhicules", "Immobilier", "Téléphones", "Mode"].map((cat) => (
                <Link
                  key={cat}
                  href={`/annonces?category=${cat.toLowerCase()}`}
                  className="px-4 py-2 bg-white/60 hover:bg-white rounded-full text-gray-700 font-medium transition-colors border border-gray-200"
                >
                  {cat}
                </Link>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Categories Section */}
      <section className="py-16">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">
            Categories populaires
          </h2>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            {CATEGORIES.map((cat) => (
              <Link
                key={cat.id}
                href={`/annonces?category=${cat.slug}`}
                className="group bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all border border-gray-100"
              >
                <div className="w-14 h-14 mx-auto mb-3 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-green-100 transition-colors">
                  <span className="text-2xl">
                    {cat.icon === "car" && "🚗"}
                    {cat.icon === "home" && "🏠"}
                    {cat.icon === "smartphone" && "📱"}
                    {cat.icon === "laptop" && "💻"}
                    {cat.icon === "tv" && "📺"}
                    {cat.icon === "shopping-bag" && "🛍️"}
                    {cat.icon === "shirt" && "👕"}
                    {cat.icon === "briefcase" && "💼"}
                    {cat.icon === "gamepad-2" && "🎮"}
                    {cat.icon === "paw-print" && "🐾"}
                    {cat.icon === "ellipsis" && "📦"}
                  </span>
                </div>
                <span className="text-sm font-semibold text-gray-900">{cat.name}</span>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Featured Listings */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-gray-900 mb-8">
            Annonces en vedette
          </h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {isLoading
              ? Array.from({ length: 4 }).map((_, i) => (
                  <div key={i} className="bg-white rounded-2xl overflow-hidden">
                    <div className="aspect-square skeleton" />
                    <div className="p-4 space-y-3">
                      <div className="h-4 skeleton w-3/4" />
                      <div className="h-4 skeleton w-1/2" />
                      <div className="h-6 skeleton w-1/3" />
                    </div>
                  </div>
                ))
              : (featuredListings?.data || []).slice(0, 4).map((listing) => (
                  <a
                    key={listing.id}
                    href={`/annonces/${listing.slug}`}
                    className="block bg-white rounded-2xl overflow-hidden hover:shadow-lg transition-shadow"
                  >
                    <div className="aspect-square relative bg-gray-100">
                      {listing.images[0] ? (
                        <img
                          src={listing.images[0].url}
                          alt={listing.title}
                          className="w-full h-full object-cover"
                        />
                      ) : (
                        <div className="w-full h-full flex items-center justify-center text-gray-300 text-4xl">
                          📷
                        </div>
                      )}
                    </div>
                    <div className="p-4">
                      <p className="text-xs text-green-600 font-medium uppercase">
                        {listing.category.name}
                      </p>
                      <h3 className="font-semibold text-gray-900 mt-1 line-clamp-2">
                        {listing.title}
                      </h3>
                      <p className="text-lg font-bold text-green-700 mt-2">
                        {listing.price.toLocaleString("fr-MA")} MAD
                      </p>
                      <p className="text-sm text-gray-500 mt-1">
                        {listing.city.name} · {listing.viewsCount} vues
                      </p>
                    </div>
                  </a>
                ))}
          </div>
        </div>
      </section>

      {/* How it Works */}
      <section className="py-16">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-gray-900 mb-12 text-center">
            Comment ça marche ?
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              { step: "1", title: "Déposez votre annonce", desc: "Remplissez notre formulaire en quelques minutes" },
              { step: "2", title: "Recevez des messages", desc: "Les acheteurs vous contactent directement" },
              { step: "3", title: "Vendez facilement", desc: "Concluez la transaction en toute simplicité" },
            ].map((item) => (
              <div key={item.step} className="text-center">
                <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <span className="text-2xl font-bold text-green-600">{item.step}</span>
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">{item.title}</h3>
                <p className="text-gray-600">{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
