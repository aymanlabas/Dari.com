import { useMutation, useQueryClient } from "@tanstack/react-query";
import { api } from "./client";
import type { RegisterData } from "@/components/auth/auth-context";

export function useLoginMutation() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: { email: string; password: string }) =>
      api.post<{ token: string; user: any }>("/api/auth/login", data),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}

export function useRegisterMutation() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: RegisterData) =>
      api.post<{ token: string; user: any }>("/api/auth/register", data),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}

export function useCreateListingMutation() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: { title: string; description: string; price: string; condition: string; categoryId: string; regionId: string; cityId: string; neighborhood: string; phone: string; images: string[] }) =>
      api.post<{ id: string }>("/api/listings", data),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}

export function useToggleFavoriteMutation() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (listingId: string) =>
      api.post<{ success: boolean }>("/api/favorites", { listingId }),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}

export function useSendMessageMutation() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: { conversationId: string; content: string }) =>
      api.post<{ id: string }>("/api/messages", data),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}

export function useReportMutation() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (data: { listingId: string; reason: string; description: string }) =>
      api.post<{ success: boolean }>("/api/reports", data),
    onSuccess: () => {
      qc.invalidateQueries();
    },
  });
}
