"use client";

import { AdminLayout } from "@/components/admin/admin-layout";

export default function AdminReportsPage() {
  return (
    <AdminLayout>
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-8">Signalements</h1>
        <div className="bg-white rounded-2xl border overflow-hidden">
          <div className="p-6 border-b">
            <p className="text-gray-500">Modérez les signalements des utilisateurs</p>
          </div>
          <div className="p-8 text-center text-gray-500">Chargement...</div>
        </div>
      </div>
    </AdminLayout>
  );
}
