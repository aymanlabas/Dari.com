"use client";

import Link from "next/link";
import { useUserDashboard } from "@/lib/api/queries";
import { Package, Eye, Heart, MessageCircle, Plus, BarChart3, TrendingUp } from "lucide-react";

export default function DashboardPage() {
  const { data, isLoading } = useUserDashboard();

  const stats = [
    { label: "Annonces actives", value: data?.activeListings || 0, icon: Package, color: "bg-green-50 text-green-600" },
    { label: "Vues", value: data?.totalViews || 0, icon: Eye, color: "bg-blue-50 text-blue-600" },
    { label: "Favoris", value: data?.totalFavorites || 0, icon: Heart, color: "bg-red-50 text-red-600" },
    { label: "Messages", value: data?.totalMessages || 0, icon: MessageCircle, color: "bg-purple-50 text-purple-600" },
  ];

  return (
    <div className="container mx-auto px-4 py-8">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Tableau de bord</h1>
          <p className="text-gray-600 mt-1">Bienvenue sur votre espace</p>
        </div>
        <Link
          href="/annonces/new"
          className="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors"
        >
          <Plus className="w-4 h-4" />
          Nouvelle annonce
        </Link>
      </div>

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {stats.map((stat) => (
          <div key={stat.label} className="bg-white rounded-2xl p-6 border border-gray-100">
            <div className="flex items-center gap-3">
              <div className={`w-12 h-12 rounded-xl ${stat.color} flex items-center justify-center`}>
                <stat.icon className="w-6 h-6" />
              </div>
              <div>
                <p className="text-2xl font-bold text-gray-900">{stat.value}</p>
                <p className="text-sm text-gray-500">{stat.label}</p>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2 bg-white rounded-2xl border">
          <div className="p-6 border-b">
            <h2 className="text-xl font-bold text-gray-900">Mes annonces</h2>
          </div>
          <div className="divide-y">
            {(data?.recentListings || []).length === 0 ? (
              <div className="p-8 text-center text-gray-500">Aucune annonce</div>
            ) : (
              (data?.recentListings || []).map((listing: any) => (
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
                    <p className="text-sm text-gray-500">{listing.category?.name} · {listing.city?.name}</p>
                  </div>
                  <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                    listing.status === "published" ? "bg-green-100 text-green-700" : "bg-gray-100 text-gray-700"
                  }`}>
                    {listing.status}
                  </span>
                </div>
              ))
            )}
          </div>
        </div>

        <div className="space-y-4">
          {[
            { href: "/dashboard/listings", label: "Mes annonces", icon: Package },
            { href: "/dashboard/favorites", label: "Favoris", icon: Heart },
            { href: "/dashboard/messages", label: "Messages", icon: MessageCircle },
            { href: "/dashboard/profile", label: "Profil", icon: BarChart3 },
            { href: "/dashboard/settings", label: "Paramètres", icon: TrendingUp },
          ].map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className="flex items-center gap-3 p-4 bg-white rounded-xl border border-gray-100 hover:bg-green-50 hover:border-green-200 transition-colors"
            >
              <link.icon className="w-5 h-5 text-gray-600" />
              <span className="font-medium text-gray-900">{link.label}</span>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
