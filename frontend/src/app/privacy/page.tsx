import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Politique de confidentialité — L9it",
};

export default function PrivacyPage() {
  return (
    <div className="container mx-auto px-4 py-8 max-w-3xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">
        Politique de confidentialité
      </h1>
      <div className="bg-white rounded-2xl border p-8 prose text-gray-600">
        <p>Dernière mise à jour: 2026</p>
        <h2>1. Collecte de données</h2>
        <p>
          Nous collectons les données nécessaires à votre inscription et à l'utilisation de la plateforme.
        </p>
        <h2>2. Utilisation des données</h2>
        <p>
          Vos données sont utilisées uniquement pour le fonctionnement de L9it.
        </p>
        <h2>3. Partage</h2>
        <p>
          Nous ne partageons pas vos données avec des tiers, sauf obligation légale.
        </p>
        <h2>4. Conservation</h2>
        <p>
          Vos données sont conservées tant que votre compte est actif.
        </p>
      </div>
    </div>
  );
}
