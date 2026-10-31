"use client";

import { useFavorites } from "@/lib/api/queries";
import { ListingCard } from "@/components/listing/listing-card";
import { LoadingSkeleton } from "@/components/common/loading-skeleton";
import { EmptyState } from "@/components/common";

export default function FavoritesPage() {
  const { data, isLoading } = useFavorites();

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Mes favoris</h1>

      {isLoading ? (
        <LoadingSkeleton count={4} />
      ) : !data?.length ? (
        <EmptyState message="Aucun favori" icon="♥️" action={{ label: "Explorer les annonces", href: "/annonces" }} />
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {data.map((listing: any) => (
            <ListingCard key={listing.id} listing={listing} />
          ))}
        </div>
      )}
    </div>
  );
}
