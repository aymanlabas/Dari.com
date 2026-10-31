import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Contact — L9it",
};

export default function ContactPage() {
  return (
    <div className="container mx-auto px-4 py-8 max-w-3xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Contact</h1>
      <div className="bg-white rounded-2xl border p-8">
        <p className="text-gray-600 mb-6">
          Contactez-nous pour toute question ou assistance.
        </p>
        <div className="space-y-4 text-gray-600">
          <p>📍 Casablanca, Maroc</p>
          <p>📞 +212 5xxx xxxx</p>
          <p>✉️ contact@l9it.ma</p>
        </div>
      </div>
    </div>
  );
}
