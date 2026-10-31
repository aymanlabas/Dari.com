export const API_URL = process.env.NEXT_PUBLIC_API_URL || "http://localhost:8000";

export const CATEGORIES = [
  { id: "vehicles", name: "Véhicules", slug: "vehicles", icon: "car" },
  { id: "immobilier", name: "Immobilier", slug: "immobilier", icon: "home" },
  { id: "telephones", name: "Téléphones & Tablettes", slug: "telephones", icon: "smartphone" },
  { id: "informatique", name: "Informatique", slug: "informatique", icon: "laptop" },
  { id: "electronique", name: "Électronique", slug: "electronique", icon: "tv" },
  { id: "maison", name: "Maison", slug: "maison", icon: "shopping-bag" },
  { id: "mode", name: "Mode", slug: "mode", icon: "shirt" },
  { id: "emploi", name: "Emploi & Services", slug: "emploi", icon: "briefcase" },
  { id: "loisirs", name: "Loisirs", slug: "loisirs", icon: "gamepad-2" },
  { id: "animaux", name: "Animaux", slug: "animaux", icon: "paw-print" },
  { id: "autres", name: "Autres", slug: "autres", icon: "ellipsis" },
] as const;

export const CONDITIONS = [
  "Neuf",
  "Comme neuf",
  "Bon état",
  "État moyen",
  "À réparer",
] as const;

export const CURRENCY = {
  code: "MAD",
  symbol: "MAD",
  name: "Dirham marocain",
} as const;

export const REGIONS = [
  { id: "casablanca-settat", name: "Casablanca-Settat", slug: "casablanca-settat", code: "06" },
  { id: "rabat-salie-kenitra", name: "Rabat-Salé-Kénitra", slug: "rabat-salie-kenitra", code: "07" },
  { id: "marrakech-safi", name: "Marrakech-Safi", slug: "marrakech-safi", code: "08" },
  { id: "fes-meknes", name: "Fès-Meknès", slug: "fes-meknes", code: "03" },
  { id: "tanger-tetouan", name: "Tanger-Tétouan-Al Hoceïma", slug: "tanger-tetouan", code: "01" },
  { id: "oriental", name: "Oriental", slug: "oriental", code: "02" },
  { id: "chashti-beni-mellal", name: "Chchaouia-Béni Mellal", slug: "chashti-beni-mellal", code: "09" },
  { id: "draa-tafilalet", name: "Drâa-Tafilalet", slug: "draa-tafilalet", code: "10" },
  { id: "souss-massa", name: "Souss-Massa", slug: "souss-massa", code: "11" },
  { id: "guelmim-oued-noun", name: "Guelmim-Oued Noun", slug: "guelmim-oued-noun", code: "12" },
  { id: "laayoune-sakia-el-hamra", name: "Laâyoune-Sakia El Hamra", slug: "laayoune-sakia-el-hamra", code: "13" },
  { id: "dakhla-oued-ed-dahab", name: "Dakhla-Oued Ed-Dahab", slug: "dakhla-oued-ed-dahab", code: "14" },
] as const;

export const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
export const ALLOWED_IMAGE_TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

export function formatPrice(price: number): string {
  return new Intl.NumberFormat("fr-MA", {
    style: "decimal",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(price) + " MAD";
}

export function formatDate(dateString: string): string {
  const date = new Date(dateString);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));

  if (days === 0) return "Aujourd'hui";
  if (days === 1) return "Hier";
  if (days < 7) return `Il y a ${days} jours`;
  if (days < 30) return `Il y a ${Math.floor(days / 7)} semaines`;
  return date.toLocaleDateString("fr-MA", { day: "numeric", month: "long", year: "numeric" });
}

export function cn(...classes: (string | boolean | undefined | null)[]): string {
  return classes.filter(Boolean).join(" ");
}
