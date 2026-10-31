import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Aide — L9it",
};

export default function HelpPage() {
  return (
    <div className="container mx-auto px-4 py-8 max-w-3xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Centre d'aide</h1>
      <div className="bg-white rounded-2xl border p-8 space-y-6">
        <div>
          <h3 className="font-bold text-gray-900">Comment publier une annonce ?</h3>
          <p className="text-gray-600 mt-1">Suivez les étapes guidées dans le formulaire de dépôt.</p>
        </div>
        <div>
          <h3 className="font-bold text-gray-900">Comment contacter un vendeur ?</h3>
          <p className="text-gray-600 mt-1">Utilisez le bouton "Contacter" sur la page de l'annonce.</p>
        </div>
        <div>
          <h3 className="font-bold text-gray-900">Comment signaler une annonce ?</h3>
          <p className="text-gray-600 mt-1">Cliquez sur "Signaler" sur la fiche produit.</p>
        </div>
        <div>
          <h3 className="font-bold text-gray-900">Comment supprimer mon compte ?</h3>
          <p className="text-gray-600 mt-1">Allez dans Paramètres &gt; Danger Zone.</p>
        </div>
        <div>
          <h3 className="font-bold text-gray-900">Quels types de fichiers sont acceptés ?</h3>
          <p className="text-gray-600 mt-1">JPG, PNG, WEBP - Maximum 5MB par image.</p>
        </div>
      </div>
    </div>
  );
}
