"use client";

import { AdminLayout } from "@/components/admin/admin-layout";

export default function AdminSettingsPage() {
  return (
    <AdminLayout>
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-8">Paramètres de la plateforme</h1>
        <div className="bg-white rounded-2xl border p-8 space-y-6">
          <h3 className="font-bold text-lg">Configuration générale</h3>
          <p className="text-gray-500">Configurez les paramètres globaux de L9it</p>
        </div>
      </div>
    </AdminLayout>
  );
}
