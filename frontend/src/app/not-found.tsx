import { Metadata } from "next";

export const metadata: Metadata = {
  title: "404 - Page non trouvée | L9it",
  description: "La page que vous recherchez n'existe plus.",
};

export default function NotFound() {
  return (
    <div className="min-h-[80vh] flex items-center justify-center px-4">
      <div className="text-center">
        <h1 className="text-8xl font-bold text-green-600 mb-4">404</h1>
        <h2 className="text-2xl font-bold text-gray-900 mb-2">Cette annonce n'existe plus.</h2>
        <p className="text-gray-600 mb-8">L'annonce que vous recherchez a été supprimée ou déplacée.</p>
        <a href="/" className="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold inline-block transition-colors">Retour à l'accueil</a>
      </div>
    </div>
  );
}
