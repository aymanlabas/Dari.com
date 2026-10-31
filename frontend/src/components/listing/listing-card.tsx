"use client";

import { useAuth } from "@/components/auth/auth-context";
import { Heart } from "lucide-react";
import Link from "next/link";
import { useToggleFavoriteMutation } from "@/lib/api/mutations";
import { Badge } from "@/components/ui";
import { cn } from "@/lib/utils";
import { formatPrice, formatDate } from "@/lib/utils/format";
import { type Listing } from "@/types/api";

interface ListingCardProps {
  listing: Listing;
}

export function ListingCard({ listing }: ListingCardProps) {
  const { isAuthenticated, user } = useAuth();
  const toggleFav = useToggleFavoriteMutation();

  const handleFavorite = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (!isAuthenticated || !user) return;
    toggleFav.mutate(listing.id);
  };

  return (
    <Link href={`/annonces/${listing.slug}`} className="block group">
      <div className="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg hover:-translate-y-0.5 transition-all">
        <div className="aspect-[4/3] relative overflow-hidden bg-gray-100">
          {listing.images?.[0]?.url ? (
            <img
              src={listing.images[0].url}
              alt={listing.title}
              className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
          ) : (
            <div className="w-full h-full flex items-center justify-center text-gray-300 text-4xl">
              📷
            </div>
          )}
          <div className="absolute top-3 left-3">
            <Badge variant="success">{listing.condition}</Badge>
          </div>
          {isAuthenticated && user && (
            <button
              onClick={handleFavorite}
              className="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-colors"
            >
              <Heart className="w-4 h-4" fill={user ? "currentColor" : "none"} />
            </button>
          )}
        </div>
        <div className="p-4">
          <p className="text-xs font-medium text-green-600 uppercase tracking-wide">
            {listing.category.name}
          </p>
          <h3 className="font-semibold text-gray-900 mt-1 line-clamp-2 group-hover:text-green-700 transition-colors">
            {listing.title}
          </h3>
          <p className="text-lg font-bold text-green-700 mt-2">
            {formatPrice(listing.price)} MAD
          </p>
          <div className="flex items-center justify-between mt-2 text-xs text-gray-500">
            <span className="flex items-center gap-1">
              <span>📍</span> {listing.city.name}
            </span>
            <span>{formatDate(listing.created_at || listing.createdAt || "")}</span>
          </div>
          <div className="flex items-center gap-2 mt-3 pt-3 border-t">
            <div className="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center">
              <span className="text-xs font-bold text-green-700">
                {listing.seller.firstName[0]}
                {listing.seller.lastName[0]}
              </span>
            </div>
            <span className="text-xs text-gray-600">
              {listing.seller.firstName} {listing.seller.lastName}
            </span>
          </div>
        </div>
      </div>
    </Link>
  );
}
