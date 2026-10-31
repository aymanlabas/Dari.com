"use client";

import Image from "next/image";
import { Heart, MessageCircle, MapPin, Calendar, Package, Star } from "lucide-react";
import { useState, use } from "react";
import { useListing } from "@/lib/api/queries";
import { formatPrice, formatDate } from "@/lib/utils";
import { useAuth } from "@/components/auth/auth-context";
import { useToggleFavoriteMutation, useReportMutation } from "@/lib/api/mutations";
import { toast } from "react-hot-toast";

export default function ListingDetailPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = use(params);
  const { data: listing, isLoading } = useListing(slug);
  const { isAuthenticated, user } = useAuth();
  const toggleFav = useToggleFavoriteMutation();
  const reportMutation = useReportMutation();
  const [showReport, setShowReport] = useState(false);
  const [reportReason, setReportReason] = useState("");

  if (isLoading) {
    return (
      <div className="container mx-auto px-4 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div className="aspect-[4/3] skeleton rounded-2xl" />
          <div className="space-y-4">
            <div className="h-8 skeleton w-3/4" />
            <div className="h-6 skeleton w-1/4" />
            <div className="h-4 skeleton w-1/2" />
            <div className="h-20 skeleton" />
            <div className="h-12 skeleton" />
          </div>
        </div>
      </div>
    );
  }

  if (!listing) {
    return <div className="container mx-auto px-4 py-8">Annonce introuvable.</div>;
  }

  const handleReport = () => {
    reportMutation.mutate(
      { listingId: listing.id, reason: reportReason, description: "" },
      {
        onSuccess: () => {
          toast.success("Signalement envoyé");
          setShowReport(false);
        },
      }
    );
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Image Gallery */}
        <div className="space-y-4">
          <div className="aspect-[4/3] rounded-2xl overflow-hidden bg-gray-100 relative">
            {(listing.images?.[0] as any)?.url ? (
              <img
                src={(listing.images?.[0] as any)?.url}
                alt={listing.title}
                className="w-full h-full object-cover"
              />
            ) : (
              <div className="w-full h-full flex items-center justify-center text-gray-300 text-6xl">
                📷
              </div>
            )}
          </div>
          {listing.images.length > 1 && (
            <div className="grid grid-cols-4 gap-2">
              {listing.images.slice(1, 5).map((img: any, i) => (
                <div key={i} className="aspect-square rounded-xl overflow-hidden bg-gray-100">
                  <img src={img?.url} alt="" className="w-full h-full object-cover" />
                </div>
              ))}
            </div>
          )}
        </div>

        {/* Details */}
        <div className="space-y-6">
          <div>
            <p className="text-sm font-medium text-green-600 uppercase">
              {listing.category.name} · {listing.condition}
            </p>
            <h1 className="text-2xl lg:text-3xl font-bold text-gray-900 mt-1">
              {listing.title}
            </h1>
            <div className="flex items-center gap-4 mt-3 text-sm text-gray-500">
              <span className="flex items-center gap-1">
                <MapPin className="w-4 h-4" />
                {listing.city.name}
              </span>
              <span className="flex items-center gap-1">
                <Calendar className="w-4 h-4" />
                {formatDate(listing.createdAt)}
              </span>
            </div>
          </div>

          <div className="text-3xl font-bold text-green-700">
            {formatPrice(listing.price)}
          </div>

          <div className="bg-gray-50 rounded-xl p-4 space-y-3">
            <h3 className="font-semibold text-gray-900">Vendeur</h3>
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <span className="font-bold text-green-700">
                  {listing.seller.firstName[0]}
                  {listing.seller.lastName[0]}
                </span>
              </div>
              <div>
                <p className="font-medium text-gray-900">
                  {listing.seller.firstName} {listing.seller.lastName}
                </p>
                {listing.seller.isVerified && (
                  <span className="text-xs text-green-600 flex items-center gap-1">
                    <Star className="w-3 h-3 fill-green-600" /> Vérifié
                  </span>
                )}
              </div>
            </div>
          </div>

          <div className="flex gap-3">
            <button className="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition-colors">
              <MessageCircle className="w-5 h-5" />
              Contacter le vendeur
            </button>
            <button className="px-4 py-3 rounded-xl border hover:bg-red-50 hover:border-red-200 transition-colors">
              <Heart className="w-5 h-5" />
            </button>
          </div>

          <div className="border-t pt-6">
            <h3 className="font-bold text-gray-900 mb-2">Description</h3>
            <p className="text-gray-600 leading-relaxed">{listing.description}</p>
          </div>

          <button
            onClick={() => setShowReport(true)}
            className="text-sm text-gray-500 hover:text-red-600 underline"
          >
            Signaler cette annonce
          </button>

          {showReport && (
            <div className="bg-red-50 rounded-xl p-4 space-y-3">
              <h4 className="font-semibold text-red-900">Signaler</h4>
              <select
                value={reportReason}
                onChange={(e) => setReportReason(e.target.value)}
                className="w-full px-3 py-2 border rounded-lg text-sm"
              >
                <option value="">Sélectionnez une raison</option>
                <option value="arnaque">Arnaque</option>
                <option value="faux-produit">Faux produit</option>
                <option value="contenu-interdit">Contenu interdit</option>
                <option value="prix-incorrect">Prix incorrect</option>
                <option value="doublon">Doublon</option>
                <option value="autre">Autre</option>
              </select>
              <button
                onClick={handleReport}
                className="w-full bg-red-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-700"
              >
                Envoyer le signalement
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
