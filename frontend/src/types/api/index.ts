export interface ApiResponse<T> {
  success: boolean;
  data: T;
  message: string;
}

export interface ApiError {
  success: boolean;
  message: string;
  errors?: Record<string, string[]>;
}

export interface PaginatedResponse<T> {
  data: T[];
  meta: {
    currentPage: number;
    pageSize: number;
    totalItems: number;
    totalPages: number;
    hasNextPage: boolean;
    hasPreviousPage: boolean;
  };
}

export interface Listing {
  id: string;
  title: string;
  slug: string;
  description: string;
  price: number;
  currency: string;
  category: Category;
  subcategory: Subcategory;
  seller: User;
  region: Region;
  city: City;
  neighborhood: string;
  images: ListingImage[];
  condition: ListingCondition;
  status: ListingStatus;
  createdAt: string;
  updatedAt: string;
  viewsCount: number;
  favoritesCount: number;
}

export interface Category {
  id: string;
  name: string;
  slug: string;
  parentId: string | null;
  parent?: Category;
  children?: Category[];
  icon: string;
}

export interface Subcategory {
  id: string;
  name: string;
  slug: string;
  categoryId: string;
  category: Category;
}

export interface User {
  id: string;
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  avatar: string | null;
  city: string;
  role: "USER" | "ADMIN";
  isVerified: boolean;
  isBlocked: boolean;
  createdAt: string;
}

export interface Region {
  id: string;
  name: string;
  slug: string;
  code: string;
}

export interface City {
  id: string;
  name: string;
  slug: string;
  regionId: string;
  region: Region;
}

export interface ListingImage {
  id: string;
  url: string;
  thumbnailUrl: string;
  isCover: boolean;
  order: number;
}

export type ListingCondition =
  | "Neuf"
  | "Comme neuf"
  | "Bon état"
  | "État moyen"
  | "À réparer";

export type ListingStatus =
  | "draft"
  | "pending"
  | "published"
  | "rejected"
  | "paused"
  | "sold"
  | "archived";

export interface SearchFilters {
  keyword?: string;
  category?: string;
  city?: string;
  region?: string;
  minPrice?: number;
  maxPrice?: number;
  condition?: ListingCondition;
  sort?: "recent" | "price_asc" | "price_desc" | "views";
  page?: number;
}

export interface Message {
  id: string;
  conversationId: string;
  senderId: string;
  content: string;
  createdAt: string;
  readAt: string | null;
}

export interface Conversation {
  id: string;
  listingId: string;
  buyerId: string;
  sellerId: string;
  listing: Listing;
  messages: Message[];
  updatedAt: string;
}

export interface Report {
  id: string;
  listingId: string;
  reason: string;
  description: string;
  createdAt: string;
  status: "pending" | "reviewed" | "resolved";
}

export interface Favorite {
  id: string;
  listingId: string;
  userId: string;
  createdAt: string;
}

export interface Notification {
  id: string;
  userId: string;
  type:
    | "new_message"
    | "listing_approved"
    | "listing_rejected"
    | "listing_sold"
    | "favorite_activity"
    | "report_result";
  title: string;
  body: string;
  read: boolean;
  createdAt: string;
  actionUrl?: string;
}
