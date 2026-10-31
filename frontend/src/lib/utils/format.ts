export function formatPrice(price: number | string): string {
  const num = typeof price === "string" ? parseFloat(price) : price;
  if (isNaN(num)) return "0 MAD";
  return new Intl.NumberFormat("fr-MA", {
    style: "decimal",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(num) + " MAD";
}

export function formatDate(dateString: string): string {
  const date = new Date(dateString.replace(" ", "T"));
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));

  if (days === 0) return "Aujourd'hui";
  if (days === 1) return "Hier";
  if (days < 7) return `Il y a ${days} jours`;
  if (days < 30) return `Il y a ${Math.floor(days / 7)} semaines`;
  return date.toLocaleDateString("fr-MA", { day: "numeric", month: "long", year: "numeric" });
}

export function generateSlug(title: string): string {
  return title
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/(^-|-$)/g, "");
}

export function generateSafeFilename(originalName: string): string {
  const ext = originalName.split(".").pop();
  const safe = originalName
    .replace(/\.[^.]+$/, "")
    .normalize("NFD")
    .replace(/[^a-z0-9]/gi, "-")
    .toLowerCase()
    .substring(0, 50);
  return `${safe}-${Date.now()}.${ext}`;
}

export function validateImageFile(file: File): { valid: boolean; error?: string } {
  const allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
  const maxSize = 5 * 1024 * 1024;

  if (!allowedTypes.includes(file.type)) {
    return { valid: false, error: "Format non supporté. Utilisez JPG, PNG ou WEBP." };
  }
  if (file.size > maxSize) {
    return { valid: false, error: "Fichier trop volumineux. Maximum 5MB." };
  }
  return { valid: true };
}
