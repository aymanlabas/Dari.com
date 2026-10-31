"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { useAuth } from "@/components/auth/auth-context";
import { Input } from "@/components/ui/input";
import { Select } from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { useCreateListingMutation } from "@/lib/api/mutations";
import { toast } from "react-hot-toast";
import { Camera, Plus, Trash2, ChevronRight, ChevronLeft } from "lucide-react";
import { CATEGORIES, CONDITIONS } from "@/lib/utils";
import { useCategories, useLocations } from "@/lib/api/queries";

const steps = ["Catégorie", "Informations", "Images", "Localisation", "Contact", "Aperçu", "Publier"];

export default function CreateListingPage() {
  const router = useRouter();
  const { isAuthenticated } = useAuth();
  const createMutation = useCreateListingMutation();
  const { data: categories } = useCategories();
  const { data: locations } = useLocations();

  const [step, setStep] = useState(0);
  const [formData, setFormData] = useState({
    categoryId: "",
    subcategoryId: "",
    title: "",
    description: "",
    price: "",
    condition: "Neuf" as string,
    images: [] as File[],
    imagePreviews: [] as string[],
    regionId: "",
    cityId: "",
    neighborhood: "",
    phone: "",
  });

  useEffect(() => {
    if (!isAuthenticated) {
      router.push("/auth/login");
    }
  }, [isAuthenticated, router]);

  if (!isAuthenticated) {
    return null;
  }

  const handleNext = () => setStep((s) => Math.min(s + 1, steps.length - 1));
  const handleBack = () => setStep((s) => Math.max(s - 1, 0));

  const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(e.target.files || []);
    const newPreviews = files.map((f) => URL.createObjectURL(f));
    setFormData({
      ...formData,
      images: [...formData.images, ...files],
      imagePreviews: [...formData.imagePreviews, ...newPreviews],
    });
  };

  const removeImage = (index: number) => {
    setFormData({
      ...formData,
      images: formData.images.filter((_, i) => i !== index),
      imagePreviews: formData.imagePreviews.filter((_, i) => i !== index),
    });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const imageBase64s = await Promise.all(
      formData.images.map((img) =>
        new Promise<string>((resolve) => {
          const reader = new FileReader();
          reader.onloadend = () => resolve(reader.result as string);
          reader.readAsDataURL(img);
        })
      )
    );
    const data = {
      title: formData.title,
      description: formData.description,
      price: formData.price,
      condition: formData.condition,
      categoryId: formData.categoryId,
      regionId: formData.regionId,
      cityId: formData.cityId,
      neighborhood: formData.neighborhood,
      phone: formData.phone,
      images: imageBase64s,
    };

    try {
      await createMutation.mutateAsync(data);
      toast.success("Annonce publiée avec succès !");
      router.push("/dashboard");
    } catch (err: any) {
      toast.error(err.message);
    }
  };

  return (
    <div className="container mx-auto px-4 py-8 max-w-4xl mx-auto">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Déposer une annonce</h1>

      {/* Step Indicator */}
      <div className="flex items-center mb-8 overflow-x-auto">
        {steps.map((s, i) => (
          <div key={s} className="flex items-center">
            <div
              className={`w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold flex-shrink-0 ${
                i <= step ? "bg-green-600 text-white" : "bg-gray-200 text-gray-500"
              }`}
            >
              {i < step ? "✓" : i + 1}
            </div>
            <span className={`ml-2 text-sm hidden sm:block ${i <= step ? "text-green-700 font-medium" : "text-gray-500"}`}>
              {s}
            </span>
            {i < steps.length - 1 && (
              <div className={`flex-1 h-0.5 mx-2 min-w-[2rem] ${i < step ? "bg-green-600" : "bg-gray-200"}`} />
            )}
          </div>
        ))}
      </div>

      <form onSubmit={handleSubmit} className="bg-white rounded-2xl border p-8">
        {/* Step 1: Category */}
        {step === 0 && (
          <div className="space-y-4">
            <h2 className="text-xl font-bold">Catégorie</h2>
            <Select value={formData.categoryId} onValueChange={(v) => setFormData({ ...formData, categoryId: v })}>
              <option value="">Choisir une catégorie</option>
              {categories?.map((cat) => (
                <option key={cat.id} value={cat.id}>{cat.name}</option>
              ))}
            </Select>
          </div>
        )}

        {/* Step 2: Basic Info */}
        {step === 1 && (
          <div className="space-y-4">
            <h2 className="text-xl font-bold">Informations de base</h2>
            <div>
              <label className="text-sm font-medium mb-1 block">Titre</label>
              <input className="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
                value={formData.title} onChange={(e) => setFormData({ ...formData, title: e.target.value })} required />
            </div>
            <div>
              <label className="text-sm font-medium mb-1 block">Description</label>
              <textarea className="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 h-32"
                value={formData.description} onChange={(e) => setFormData({ ...formData, description: e.target.value })} required />
            </div>
            <div className="grid grid-cols-2 gap-3">
              <div>
                <label className="text-sm font-medium mb-1 block">Prix (MAD)</label>
                <input type="number" className="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
                  value={formData.price} onChange={(e) => setFormData({ ...formData, price: e.target.value })} required />
              </div>
              <div>
                <label className="text-sm font-medium mb-1 block">État</label>
                <select className="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
                  value={formData.condition} onChange={(e) => setFormData({ ...formData, condition: e.target.value })}>
                  {CONDITIONS.map((c) => <option key={c} value={c}>{c}</option>)}
                </select>
              </div>
            </div>
          </div>
        )}

        {/* Step 3: Images */}
        {step === 2 && (
          <div className="space-y-4">
            <h2 className="text-xl font-bold">Images</h2>
            <div className="border-2 border-dashed rounded-xl p-8 text-center hover:border-green-500 transition-colors">
              <Camera className="w-8 h-8 mx-auto text-gray-400 mb-2" />
              <p className="text-sm text-gray-500 mb-2">Glissez-déposez vos images ici</p>
              <label className="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg cursor-pointer inline-block text-sm font-medium">
                Choisir des fichiers
                <input type="file" multiple accept="image/*" className="hidden" onChange={handleImageUpload} />
              </label>
            </div>
            {formData.imagePreviews.length > 0 && (
              <div className="grid grid-cols-4 gap-3">
                {formData.imagePreviews.map((preview, i) => (
                  <div key={i} className="relative aspect-square rounded-lg overflow-hidden">
                    <img src={preview} alt="" className="w-full h-full object-cover" />
                    <button type="button" onClick={() => removeImage(i)} className="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                      <Trash2 className="w-3 h-3" />
                    </button>
                  </div>
                ))}
              </div>
            )}
          </div>
        )}

        {/* Step 4: Location */}
        {step === 3 && (
          <div className="space-y-4">
            <h2 className="text-xl font-bold">Localisation</h2>
            <Select value={formData.regionId} onValueChange={(v) => setFormData({ ...formData, regionId: v })}>
              <option value="">Région</option>
              {locations?.regions.map((r) => <option key={r.id} value={r.id}>{r.name}</option>)}
            </Select>
            <Select>
              <option value="">Ville</option>
              {/* Cities based on selected region */}
            </Select>
            <input className="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
              placeholder="Quartier"
              value={formData.neighborhood}
              onChange={(e) => setFormData({ ...formData, neighborhood: e.target.value })} />
          </div>
        )}

        {/* Step 5: Contact */}
        {step === 4 && (
          <div className="space-y-4">
            <h2 className="text-xl font-bold">Contact</h2>
            <div>
              <label className="text-sm font-medium mb-1 block">Téléphone</label>
              <input type="tel" className="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500"
                value={formData.phone} onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                placeholder="+212 6 XX XX XX XX" required />
            </div>
          </div>
        )}

        {/* Step 6: Preview */}
        {step === 5 && (
          <div className="space-y-4">
            <h2 className="text-xl font-bold">Aperçu</h2>
            <div className="bg-gray-50 rounded-xl p-6 space-y-3">
              <p><span className="font-medium">Catégorie:</span> {formData.categoryId}</p>
              <p><span className="font-medium">Titre:</span> {formData.title}</p>
              <p><span className="font-medium">Prix:</span> {formData.price} MAD</p>
              <p><span className="font-medium">État:</span> {formData.condition}</p>
              <p><span className="font-medium">Localisation:</span> {formData.neighborhood}</p>
              {formData.imagePreviews.length > 0 && (
                <div className="grid grid-cols-4 gap-2 mt-2">
                  {formData.imagePreviews.slice(0, 4).map((p, i) => (
                    <img key={i} src={p} alt="" className="aspect-square rounded-lg object-cover" />
                  ))}
                </div>
              )}
            </div>
          </div>
        )}

        {/* Navigation */}
        <div className="flex justify-between mt-8">
          <button
            type="button"
            onClick={handleBack}
            disabled={step === 0}
            className="flex items-center gap-2 px-6 py-3 border rounded-xl font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
          >
            <ChevronLeft className="w-4 h-4" /> Précédent
          </button>
          {step < steps.length - 1 ? (
            <button
              type="button"
              onClick={handleNext}
              className="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold"
            >
              Suivant <ChevronRight className="w-4 h-4" />
            </button>
          ) : (
            <button
              type="submit"
              disabled={createMutation.isPending}
              className="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-8 py-3 rounded-xl font-semibold flex items-center gap-2"
            >
              {createMutation.isPending ? "Publication..." : "Publier l'annonce"}
            </button>
          )}
        </div>
      </form>
    </div>
  );
}
