export const getUsers = async (name?: string) => {
  const params: Record<string, string> = {};

  return await useApi("users", {
    method: "GET",
    params: {
      name,
    },
  });
};
