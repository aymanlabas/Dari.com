import { Metadata } from "next";

export const metadata: Metadata = {
  title: "À propos — L9it",
};

export default function AboutPage() {
  return (
    <div className="container mx-auto px-4 py-8 max-w-3xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">À propos de L9it</h1>
      <div className="bg-white rounded-2xl border p-8">
        <p className="text-lg text-gray-600 mb-4">
          L9it est le marketplace marocain moderne qui vous permet de trouver, acheter et vendre facilement.
        </p>
        <p className="text-gray-600 mb-4">
          Notre mission: connecter les Marocains à travers un marché en ligne simple, sûr et efficace.
        </p>
        <h2 className="text-xl font-bold mt-6 mb-3">Notre vision</h2>
        <p className="text-gray-600">
          Devenir la première plateforme commerciale du Maroc.
        </p>
      </div>
    </div>
  );
}
