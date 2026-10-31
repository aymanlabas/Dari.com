"use client";

import { AdminLayout } from "@/components/admin/admin-layout";
import { useAdminStats } from "@/lib/api/queries";
import { Users, Package, CheckCircle, Clock, Flag, ShoppingBag, Eye, TrendingUp } from "lucide-react";

export default function AdminPage() {
  const { data, isLoading } = useAdminStats();

  const stats = [
    { label: "Utilisateurs", value: data?.stats?.totalUsers || 0, icon: Users, color: "bg-blue-50 text-blue-600" },
    { label: "Total annonces", value: data?.stats?.totalListings || 0, icon: Package, color: "bg-purple-50 text-purple-600" },
    { label: "Publiées", value: data?.stats?.publishedListings || 0, icon: CheckCircle, color: "bg-green-50 text-green-600" },
    { label: "En attente", value: data?.stats?.pendingListings || 0, icon: Clock, color: "bg-yellow-50 text-yellow-600" },
    { label: "Signalées", value: data?.stats?.reportedListings || 0, icon: Flag, color: "bg-red-50 text-red-600" },
    { label: "Vendues", value: data?.stats?.soldListings || 0, icon: ShoppingBag, color: "bg-orange-50 text-orange-600" },
    { label: "Vues", value: data?.stats?.totalViews || 0, icon: Eye, color: "bg-indigo-50 text-indigo-600" },
    { label: "Croissance", value: "+12%", icon: TrendingUp, color: "bg-emerald-50 text-emerald-600" },
  ];

  return (
    <AdminLayout>
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-8">Tableau de bord admin</h1>
        <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
          {stats.map((stat) => (
            <div key={stat.label} className="bg-white rounded-xl p-4 border border-gray-100">
              <div className={`w-10 h-10 rounded-lg ${stat.color} flex items-center justify-center mb-2`}>
                <stat.icon className="w-5 h-5" />
              </div>
              <p className="text-xl font-bold text-gray-900">{stat.value}</p>
              <p className="text-xs text-gray-500">{stat.label}</p>
            </div>
          ))}
        </div>
      </div>
    </AdminLayout>
  );
}
