const API_BASE = process.env.NEXT_PUBLIC_API_URL || "http://localhost";

interface RequestOptions {
  method?: string;
  body?: unknown;
  headers?: Record<string, string>;
}

async function request<T>(url: string, options: RequestOptions = {}): Promise<T> {
  const token = typeof window !== "undefined" ? localStorage.getItem("token") : null;

  const isFormData = options.body instanceof FormData;

  const headers: Record<string, string> = {
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    ...options.headers,
  };

  if (!isFormData) {
    headers["Content-Type"] = "application/json";
  }

  const config: RequestInit = {
    method: options.method || "GET",
    headers,
    body: isFormData ? (options.body as FormData) : JSON.stringify(options.body),
  };

  const res = await fetch(`${API_BASE}${url}`, config);

  if (!res.ok) {
    const error = await res.json().catch(() => ({ message: "An error occurred" }));
    const err = new Error(error.message || `HTTP ${res.status}`) as Error & { errors?: Record<string, string[]> };
    err.errors = error.errors;
    throw err;
  }

  const json = await res.json();

  if (json && typeof json === "object" && "data" in json && "success" in json) {
    if ("meta" in json) {
      return { data: json.data, meta: json.meta } as T;
    }
    return json.data as T;
  }

  return json as T;
}

export const api = {
  get: <T>(url: string) => request<T>(url),
  post: <T>(url: string, body: unknown) => request<T>(url, { method: "POST", body }),
  put: <T>(url: string, body: unknown) => request<T>(url, { method: "PUT", body }),
  delete: <T>(url: string) => request<T>(url, { method: "DELETE" }),
};

export function getApiBase(): string {
  return API_BASE;
}
