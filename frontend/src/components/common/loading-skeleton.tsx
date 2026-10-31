import { ReactNode } from "react";
import { cn } from "@/lib/utils";

interface LoadingSkeletonProps {
  count?: number;
}

export function LoadingSkeleton({ count = 6 }: LoadingSkeletonProps) {
  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      {Array.from({ length: count }).map((_, i) => (
        <div key={i} className="bg-white rounded-2xl overflow-hidden">
          <div className="aspect-square skeleton" />
          <div className="p-4 space-y-3">
            <div className="h-4 bg-gray-200 rounded w-3/4 skeleton" />
            <div className="h-4 bg-gray-200 rounded w-1/2 skeleton" />
            <div className="h-6 bg-gray-200 rounded w-1/3 skeleton" />
          </div>
        </div>
      ))}
    </div>
  );
}

interface EmptyStateProps {
  message?: string;
  icon?: string;
  action?: {
    label: string;
    href: string;
  };
}

export function EmptyState({
  message = "Aucune annonce trouvée",
  icon = "📭",
  action,
}: EmptyStateProps) {
  return (
    <div className="flex flex-col items-center justify-center py-16 text-center">
      <div className="text-6xl mb-4">{icon}</div>
      <h3 className="text-lg font-semibold text-gray-900 mb-2">{message}</h3>
      {action && (
        <a
          href={action.href}
          className="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors"
        >
          {action.label}
        </a>
      )}
    </div>
  );
}
