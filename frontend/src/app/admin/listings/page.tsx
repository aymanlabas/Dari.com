"use client";

import { AdminLayout } from "@/components/admin/admin-layout";

export default function AdminListingsPage() {
  return (
    <AdminLayout>
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-8">Annonces</h1>
        <div className="bg-white rounded-2xl border overflow-hidden">
          <div className="p-6 border-b">
            <p className="text-gray-500">Modérez les annonces</p>
          </div>
          <div className="p-8 text-center text-gray-500">
            <p>Annonces en cours de chargement...</p>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
