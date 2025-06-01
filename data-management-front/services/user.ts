export const getUsers = async (name?: string) => {
  return await useApi("users", {
    method: "GET",
    params: {
      name,
    },
  });
};
