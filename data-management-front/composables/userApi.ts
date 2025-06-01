import type { UseFetchOptions } from "#app";

export async function useApi<T>(
  path: string,
  options: UseFetchOptions<T> = {}
): Promise<any> {
  const config = useRuntimeConfig();
  const baseUrl = config.public.apiBase;

  if (!baseUrl) {
    throw new Error("API URL is not configured");
  }

  const url = `${baseUrl}/${path}`;

  return useFetch(url, {
    ...options,
    headers: {
      ...options.headers,
    },
    onResponseError({ request, response, options }) {
      const status = response.status;
      const toast = useToast();

      switch (status) {
        case 403:
          toast.add({
            title: "Forbidden",
            description: "Access denied.",
            color: "error",
          });
          break;

        case 500:
          toast.add({
            title: "Server Error",
            description: "Something went wrong.",
            color: "error",
          });
          break;

        default:
          toast.add({
            title: "Error",
            description: `Unexpected error (${status})`,
            color: "error",
          });
          break;
      }

      console.error("Fetch error:", response);
    },
  });
}
