export const getUsers = async () => {
  const response = await useApi("users", {
    method: "GET",
  });

  return response;
};
