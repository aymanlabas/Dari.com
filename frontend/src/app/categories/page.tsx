"use client";

import { Suspense } from "react";
import { useListings, useCategories, useLocations } from "@/lib/api/queries";
import { ListingCard } from "@/components/listing/listing-card";
import { LoadingSkeleton } from "@/components/common/loading-skeleton";
import { EmptyState } from "@/components/common";
import { useRouter, useSearchParams } from "next/navigation";

function CategoriesContent() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const category = searchParams.get("category") || "";
  const { data: listings, isLoading } = useListings({ category: category || undefined });
  const { data: categories } = useCategories();
  const { data: locations } = useLocations();

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Catégories</h1>

      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        {(categories || []).map((cat: any) => (
          <button
            key={cat.id}
            onClick={() => router.push(`/annonces?category=${cat.slug}`)}
            className={`group bg-white rounded-2xl p-6 text-center hover:shadow-lg transition-all border ${
              category === cat.slug ? "border-green-500 bg-green-50" : "border-gray-100"
            }`}
          >
            <span className="text-3xl">
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
            <p className="text-sm font-semibold text-gray-900 mt-2">{cat.name}</p>
          </button>
        ))}
      </div>

      <h2 className="text-xl font-bold text-gray-900 mb-4">Annonces</h2>
      {isLoading ? (
        <LoadingSkeleton count={6} />
      ) : !listings?.data?.length ? (
        <EmptyState message="Aucune annonce dans cette catégorie" />
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {(listings?.data || []).map((listing: any) => (
            <ListingCard key={listing.id} listing={listing} />
          ))}
        </div>
      )}
    </div>
  );
}

export default function CategoriesPage() {
  return (
    <Suspense fallback={<LoadingSkeleton count={6} />}>
      <CategoriesContent />
    </Suspense>
  );
}
