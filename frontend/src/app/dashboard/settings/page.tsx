"use client";

import { useAuth } from "@/components/auth/auth-context";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Save } from "lucide-react";

export default function SettingsPage() {
  const { user } = useAuth();

  return (
    <div className="container mx-auto px-4 py-8 max-w-3xl">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Paramètres</h1>

      <div className="bg-white rounded-2xl border p-8 space-y-8">
        <h3 className="font-bold text-lg">Préférences</h3>
        <div className="space-y-4">
          <div className="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
            <div>
              <p className="font-medium">Langue</p>
              <p className="text-sm text-gray-500">Français</p>
            </div>
            <Button variant="outline">Modifier</Button>
          </div>
          <div className="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
            <div>
              <p className="font-medium">Devise</p>
              <p className="text-sm text-gray-500">MAD (Dirham marocain)</p>
            </div>
            <Button variant="outline">Modifier</Button>
          </div>
        </div>

        <h3 className="font-bold text-lg mt-6">Danger Zone</h3>
        <div className="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-200">
          <div>
            <p className="font-medium text-red-700">Supprimer mon compte</p>
            <p className="text-sm text-red-600">Cette action est irréversible</p>
          </div>
          <Button variant="danger">Supprimer</Button>
        </div>
      </div>
    </div>
  );
}
