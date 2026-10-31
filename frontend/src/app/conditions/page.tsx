import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Termes d'utilisation — L9it",
};

export default function TermsPage() {
  return (
    <div className="container mx-auto px-4 py-8 max-w-3xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">
        Conditions d'utilisation
      </h1>
      <div className="bg-white rounded-2xl border p-8 prose text-gray-600">
        <p>Dernière mise à jour: 2026</p>
        <h2>1. Acceptation des termes</h2>
        <p>
          En utilisant L9it, vous acceptez ces conditions d'utilisation.
        </p>
        <h2>2. Utilisation de la plateforme</h2>
        <p>
          L9it est une plateforme de mise en relation entre acheteurs et vendeurs au Maroc.
        </p>
        <h2>3. Responsabilité</h2>
        <p>
          L9it ne sera pas responsable des transactions entre utilisateurs.
        </p>
        <h2>4. Contenu interdit</h2>
        <p>
          Toute annonce en violation de la loi marocaine sera supprimée.
        </p>
        <h2>5. Modifications</h2>
        <p>
          Nous nous réservons le droit de modifier ces conditions à tout moment.
        </p>
      </div>
    </div>
  );
}
