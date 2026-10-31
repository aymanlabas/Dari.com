"use client";

import { api } from "@/lib/api/client";
import { useAuth } from "@/components/auth/auth-context";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useState } from "react";
import { Mail, Settings, Shield, Bell, Trash2, Save } from "lucide-react";

export default function ProfilePage() {
  const { user } = useAuth();

  return (
    <div className="container mx-auto px-4 py-8 max-w-3xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Profil</h1>

      <div className="bg-white rounded-2xl border p-8 space-y-8">
        {/* Avatar and basic info */}
        <div className="flex items-center gap-4">
          <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
            <span className="text-2xl font-bold text-green-700">
              {user?.firstName[0]}{user?.lastName[0]}
            </span>
          </div>
          <div>
            <h2 className="text-xl font-bold">{user?.firstName} {user?.lastName}</h2>
            <p className="text-sm text-gray-500">{user?.email}</p>
            {user?.isVerified && <span className="text-xs text-green-600">✓ Compte vérifié</span>}
          </div>
        </div>

        {/* Profile form */}
        <form className="space-y-4">
          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="text-sm font-medium mb-1 block">Prénom</label>
              <Input defaultValue={user?.firstName} />
            </div>
            <div>
              <label className="text-sm font-medium mb-1 block">Nom</label>
              <Input defaultValue={user?.lastName} />
            </div>
          </div>
          <div>
            <label className="text-sm font-medium mb-1 block">Email</label>
            <Input type="email" defaultValue={user?.email} />
          </div>
          <div>
            <label className="text-sm font-medium mb-1 block">Téléphone</label>
            <Input defaultValue={user?.phone} placeholder="+212 6 XX XX XX XX" />
          </div>
          <div>
            <label className="text-sm font-medium mb-1 block">Ville</label>
            <Input defaultValue={user?.city} />
          </div>
          <Button className="flex items-center gap-2">
            <Save className="w-4 h-4" /> Sauvegarder
          </Button>
        </form>
      </div>

      {/* Account Settings */}
      <div className="bg-white rounded-2xl border p-8 mt-6 space-y-6">
        <h3 className="font-bold text-lg">Sécurité</h3>
        <div className="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
          <div className="flex items-center gap-3">
            <Shield className="w-5 h-5 text-green-600" />
            <div>
              <p className="font-medium">Mot de passe</p>
              <p className="text-sm text-gray-500">Dernière modification: il y a 30 jours</p>
            </div>
          </div>
          <Button variant="outline">Modifier</Button>
        </div>
        <div className="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
          <div className="flex items-center gap-3">
            <Bell className="w-5 h-5 text-green-600" />
            <div>
              <p className="font-medium">Notifications</p>
              <p className="text-sm text-gray-500">Recevoir les alertes par email</p>
            </div>
          </div>
          <Button variant="outline">Configurer</Button>
        </div>
      </div>
    </div>
  );
}
