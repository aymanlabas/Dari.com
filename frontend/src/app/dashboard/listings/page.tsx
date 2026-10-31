"use client";

import Link from "next/link";
import { useUserDashboard } from "@/lib/api/queries";
import { Package, Edit2, Pause, Trash2, ArrowRight } from "lucide-react";
import { formatDate } from "@/lib/utils/format";

export default function ListingsPage() {
  const { data } = useUserDashboard();

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Mes annonces</h1>

      {(data?.recentListings || []).length === 0 ? (
        <div className="bg-white rounded-2xl border p-12 text-center">
          <Package className="w-16 h-16 mx-auto mb-4 text-gray-300" />
          <h2 className="text-xl font-bold text-gray-900 mb-2">Aucune annonce</h2>
          <p className="text-gray-600 mb-6">Publiez votre première annonce</p>
          <Link
            href="/annonces/new"
            className="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors"
          >
            <ArrowRight className="w-4 h-4" /> Déposer une annonce
          </Link>
        </div>
      ) : (
        <div className="bg-white rounded-2xl border overflow-hidden">
          <div className="divide-y">
            {(data?.recentListings || []).map((listing: any) => (
              <div key={listing.id} className="p-4 flex items-center gap-4 hover:bg-gray-50 transition-colors">
                <div className="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                  {listing.images?.[0]?.url ? (
                    <img src={listing.images[0].url} alt="" className="w-full h-full object-cover" />
                  ) : (
                    <div className="w-full h-full flex items-center justify-center text-gray-300">📷</div>
                  )}
                </div>
                <div className="flex-1 min-w-0">
                  <p className="font-medium text-gray-900 truncate">{listing.title}</p>
                  <p className="text-sm text-gray-500">
                    {listing.category?.name} · {listing.city?.name} · {formatDate(listing.created_at)}
                  </p>
                </div>
                <div className="flex items-center gap-2">
                  <button className="p-2 hover:bg-gray-100 rounded-lg" title="Modifier"><Edit2 className="w-4 h-4" /></button>
                  <button className="p-2 hover:bg-gray-100 rounded-lg" title="Pause"><Pause className="w-4 h-4" /></button>
                  <button className="p-2 hover:bg-red-50 rounded-lg text-red-600" title="Supprimer"><Trash2 className="w-4 h-4" /></button>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
