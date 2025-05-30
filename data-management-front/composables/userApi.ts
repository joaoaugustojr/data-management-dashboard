import type { UseFetchOptions } from "#app";

export async function useApi<T>(
  path: string,
  options: UseFetchOptions<T> = {}
): Promise<any> {
  const config = useRuntimeConfig();
  const baseUrl = config.public.apiBase;

  console.log("baseUrl", baseUrl);

  if (!baseUrl) {
    throw new Error("API URL is not configured");
  }

  const url = `${baseUrl}/${path}`;

  return useFetch(url, {
    ...options,
    headers: {
      ...options.headers,
    },
  });
}
