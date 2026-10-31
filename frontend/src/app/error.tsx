"use client";

export default function Error({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  return (
    <div className="min-h-[80vh] flex items-center justify-center px-4">
      <div className="text-center">
        <h1 className="text-8xl font-bold text-green-600 mb-4">!</h1>
        <h2 className="text-2xl font-bold text-gray-900 mb-2">Une erreur est survenue.</h2>
        <p className="text-gray-600 mb-8">Veuillez réessayer plus tard.</p>
        <button
          onClick={() => reset()}
          className="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors"
        >
          Réessayer
        </button>
      </div>
    </div>
  );
}
