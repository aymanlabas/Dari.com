import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { api } from "./client";
import type {
  Listing,
  Category,
  City,
  Region,
  SearchFilters,
  User,
  Conversation,
  Notification,
} from "@/types/api";

interface PaginatedListings {
  data: Listing[];
  meta: {
    currentPage: number;
    pageSize: number;
    totalItems: number;
    totalPages: number;
    hasNextPage: boolean;
    hasPreviousPage: boolean;
  };
}

interface DashboardData {
  activeListings: number;
  totalViews: number;
  totalFavorites: number;
  totalMessages: number;
  recentListings: Listing[];
}

interface AdminStatsData {
  stats: {
    totalUsers: number;
    totalListings: number;
    publishedListings: number;
    pendingListings: number;
    reportedListings: number;
    soldListings: number;
    totalViews: number;
  };
}

// Auth
export function useLogin() {
  return useMutation({
    mutationFn: (data: { email: string; password: string }) =>
      api.post<{ token: string; user: User }>("/api/auth/login", data),
  });
}

export function useRegister() {
  return useMutation({
    mutationFn: (data: { firstName: string; lastName: string; email: string; phone: string; password: string }) =>
      api.post<{ token: string; user: User }>("/api/auth/register", data),
  });
}

// Listings
export function useListings(filters?: SearchFilters) {
  const params = new URLSearchParams();
  if (filters?.keyword) params.set("keyword", filters.keyword);
  if (filters?.category) params.set("category", filters.category);
  if (filters?.city) params.set("city", filters.city);
  if (filters?.region) params.set("region", filters.region);
  if (filters?.minPrice) params.set("minPrice", String(filters.minPrice));
  if (filters?.maxPrice) params.set("maxPrice", String(filters.maxPrice));
  if (filters?.condition) params.set("condition", filters.condition);
  if (filters?.sort) params.set("sort", filters.sort);
  if (filters?.page) params.set("page", String(filters.page));

  return useQuery<PaginatedListings>({
    queryKey: ["listings", filters],
    queryFn: () => api.get<PaginatedListings>(`/api/listings?${params}`),
  });
}

export function useListing(slug: string) {
  return useQuery<Listing>({
    queryKey: ["listing", slug],
    queryFn: () => api.get<Listing>(`/api/listings/${slug}`),
    enabled: !!slug,
  });
}

// Categories
export function useCategories() {
  return useQuery<Category[]>({
    queryKey: ["categories"],
    queryFn: () => api.get<Category[]>("/api/categories"),
  });
}

// Locations
export function useLocations() {
  return useQuery<{ regions: Region[]; cities: City[] }>({
    queryKey: ["locations"],
    queryFn: () => api.get<{ regions: Region[]; cities: City[] }>("/api/locations"),
  });
}

// Favorites
export function useFavorites() {
  return useQuery<Listing[]>({
    queryKey: ["favorites"],
    queryFn: () => api.get<Listing[]>("/api/favorites"),
  });
}

export function useToggleFavorite() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (listingId: string) => api.post<{ success: boolean }>("/api/favorites", { listingId }),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}

// Messages
export function useConversations() {
  return useQuery<{ conversations: Conversation[] }>({
    queryKey: ["conversations"],
    queryFn: () => api.get<{ conversations: Conversation[] }>("/api/conversations"),
  });
}

export function useSendMessage() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: { conversationId: string; content: string }) =>
      api.post<{ id: string }>("/api/messages", data),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}

// Reports
export function useReport() {
  return useMutation({
    mutationFn: (data: { listingId: string; reason: string; description: string }) =>
      api.post<{ success: boolean }>("/api/reports", data),
  });
}

// Dashboard
export function useUserDashboard() {
  return useQuery<DashboardData>({
    queryKey: ["dashboard"],
    queryFn: () => api.get<DashboardData>("/api/dashboard"),
  });
}

// Admin
export function useAdminStats() {
  return useQuery<AdminStatsData>({
    queryKey: ["admin-stats"],
    queryFn: () => api.get<AdminStatsData>("/api/admin/stats"),
  });
}

// Notifications
export function useNotifications() {
  return useQuery<Notification[]>({
    queryKey: ["notifications"],
    queryFn: () => api.get<Notification[]>("/api/notifications"),
  });
}
