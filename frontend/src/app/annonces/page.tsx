"use client";

import { Suspense, useState } from "react";
import { Search, SlidersHorizontal } from "lucide-react";
import { useRouter, useSearchParams } from "next/navigation";
import { useListings, useCategories, useLocations } from "@/lib/api/queries";
import { ListingCard } from "@/components/listing/listing-card";
import { LoadingSkeleton } from "@/components/common/loading-skeleton";
import { EmptyState } from "@/components/common";
import { CATEGORIES } from "@/lib/utils";

function SearchContent() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const keyword = searchParams.get("keyword") || "";
  const category = searchParams.get("category") || "";
  const city = searchParams.get("city") || "";
  const minPrice = searchParams.get("minPrice") || "";
  const maxPrice = searchParams.get("maxPrice") || "";
  const sort = (searchParams.get("sort") as string) || "recent";

  const [searchQuery, setSearchQuery] = useState(keyword);
  const [showFilters, setShowFilters] = useState(false);

  const { data: listings, isLoading } = useListings({
    keyword: keyword || undefined,
    category: category || undefined,
    city: city || undefined,
    minPrice: minPrice ? Number(minPrice) : undefined,
    maxPrice: maxPrice ? Number(maxPrice) : undefined,
    sort: sort as any,
  });

  const { data: categories } = useCategories();
  const { data: locations } = useLocations();

  const handleSearch = () => {
    const params = new URLSearchParams();
    if (searchQuery) params.set("keyword", searchQuery);
    if (category) params.set("category", category);
    if (city) params.set("city", city);
    if (minPrice) params.set("minPrice", minPrice);
    if (maxPrice) params.set("maxPrice", maxPrice);
    if (sort !== "recent") params.set("sort", sort);
    router.push(`/annonces?${params.toString()}`);
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">
        Rechercher des annonces
      </h1>

      <div className="bg-white rounded-2xl shadow-sm border p-4 mb-8">
        <div className="flex flex-col sm:flex-row gap-3">
          <div className="flex-1 relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
            <input
              type="text"
              placeholder="Rechercher..."
              className="w-full pl-10 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              onKeyDown={(e) => e.key === "Enter" && handleSearch()}
            />
          </div>
          <button
            onClick={() => setShowFilters(!showFilters)}
            className="flex items-center gap-2 px-6 py-3 border rounded-xl font-medium text-gray-700 hover:bg-gray-50 transition-colors whitespace-nowrap"
          >
            <SlidersHorizontal className="w-4 h-4" />
            Filtres
          </button>
          <button
            onClick={handleSearch}
            className="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors whitespace-nowrap"
          >
            Rechercher
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {showFilters && (
          <div className="lg:col-span-1 space-y-4">
            <div className="bg-white rounded-2xl p-6 space-y-4">
              <h3 className="font-bold text-gray-900">Filtres</h3>
              <div>
                <label className="text-sm font-medium text-gray-700 mb-2 block">Catégorie</label>
                <select className="w-full px-3 py-2 border rounded-lg text-sm">
                  <option value="">Toutes les catégories</option>
                  {CATEGORIES.map((cat) => (
                    <option key={cat.id} value={cat.slug}>{cat.name}</option>
                  ))}
                </select>
              </div>
              <div>
                <label className="text-sm font-medium text-gray-700 mb-2 block">Ville</label>
                <select className="w-full px-3 py-2 border rounded-lg text-sm">
                  <option value="">Toutes les villes</option>
                </select>
              </div>
              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="text-sm font-medium text-gray-700 mb-2 block">Prix min</label>
                  <input type="number" placeholder="0" className="w-full px-3 py-2 border rounded-lg text-sm" />
                </div>
                <div>
                  <label className="text-sm font-medium text-gray-700 mb-2 block">Prix max</label>
                  <input type="number" placeholder="999999" className="w-full px-3 py-2 border rounded-lg text-sm" />
                </div>
              </div>
              <div>
                <label className="text-sm font-medium text-gray-700 mb-2 block">État</label>
                <select className="w-full px-3 py-2 border rounded-lg text-sm">
                  <option value="">Tout état</option>
                  <option>Neuf</option>
                  <option>Comme neuf</option>
                  <option>Bon état</option>
                  <option>État moyen</option>
                  <option>À réparer</option>
                </select>
              </div>
            </div>
          </div>
        )}

        <div className="lg:col-span-3">
          <p className="text-sm text-gray-500 mb-4">
            {listings?.meta?.totalItems || 0} annonces trouvées
          </p>
          {isLoading ? (
            <LoadingSkeleton count={8} />
          ) : !listings?.data?.length ? (
            <EmptyState message="Aucune annonce trouvée" />
          ) : (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {(listings?.data || []).map((listing: any) => (
                <ListingCard key={listing.id} listing={listing} />
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

export default function SearchPage() {
  return (
    <Suspense fallback={<LoadingSkeleton count={8} />}>
      <SearchContent />
    </Suspense>
  );
}
